SET search_path TO lbaw2162;

DROP TABLE IF EXISTS likes_member_post;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS member_notification;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS question_category;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS subscription_post;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS subscription_category;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS member_badge;
DROP TABLE IF EXISTS badge;
DROP TABLE IF EXISTS subscription_member;
DROP TABLE IF EXISTS member;

CREATE TABLE member(
    id SERIAL PRIMARY KEY,
    email TEXT NOT NULL CONSTRAINT member_email_uk UNIQUE,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    birth_date DATE NOT NULL,
    photo VARCHAR(100) DEFAULT NULL, 
    bio text DEFAULT NULL, 
	is_banned BOOLEAN NOT NULL DEFAULT FALSE,
	member_type TEXT NOT NULL DEFAULT 'member', 
    likes INT NOT NULL DEFAULT 0 /*Insert to improve efficiency. Prevents the need to run through the likes_member_post table if the likes of a member is needed*/
	CONSTRAINT m_type CHECK (member_type='member' OR member_type='mod' OR member_type='admin'),
	CONSTRAINT bd CHECK (birth_date <= NOW())
);

--subscription_member(member -> member, subscribed -> member)
CREATE TABLE subscription_member(
	subscriber INTEGER REFERENCES member(id) ON UPDATE CASCADE ON DELETE CASCADE,
    subscribed INTEGER REFERENCES member(id) ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY (subscriber, subscribed),
	CONSTRAINT himself CHECK (subscriber != subscribed)
);

CREATE TABLE badge(
	id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE member_badge(
    member INTEGER REFERENCES member(id) ON UPDATE CASCADE ON DELETE CASCADE,
    badge INTEGER REFERENCES badge(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (member, badge)
);

CREATE TABLE category(
	id serial PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE subscription_category(
    member INTEGER REFERENCES member(id) ON UPDATE CASCADE ON DELETE CASCADE,
    category INTEGER REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (member, category)
);

CREATE TABLE post(
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    publish_date DATE NOT NULL DEFAULT CURRENT_DATE,
    likes INTEGER NOT NULL DEFAULT 0 CHECK (likes >= 0),
    dislikes INTEGER NOT NULL DEFAULT 0 CHECK(dislikes >= 0),
    is_edited BOOLEAN NOT NULL DEFAULT FALSE,
    edition_date DATE DEFAULT NULL,
    member INTEGER NOT NULL DEFAULT -1 REFERENCES member(id) ON UPDATE CASCADE ON DELETE SET DEFAULT,
	CONSTRAINT p_date CHECK (publish_date <= NOW()),
	CONSTRAINT e_date CHECK (edition_date <= NOW() AND publish_date <=edition_date),
	CONSTRAINT e_date_is_edit CHECK ((edition_date IS NULL AND is_edited = FALSE) OR (NOT (edition_date IS NULL) AND is_edited = TRUE))
);


CREATE TABLE subscription_post(
    member INTEGER REFERENCES member(id) ON UPDATE CASCADE ON DELETE CASCADE,
    post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (member, post)
    --o user subscreve automaticamente o seu post??
);

CREATE TABLE likes_member_post(
    member INTEGER REFERENCES member(id) ON UPDATE CASCADE ON DELETE CASCADE,
    post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    likes INTEGER NOT NULL CHECK(likes = 1 OR likes = (-1)),
    PRIMARY KEY (member, post)
);

CREATE TABLE question(
    post INTEGER PRIMARY KEY REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    is_answered BOOLEAN NOT NULL DEFAULT FALSE,
    title TEXT NOT NULL
);

CREATE TABLE question_category(
	question INTEGER REFERENCES question(post) ON UPDATE CASCADE ON DELETE CASCADE,
    category INTEGER REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (question, category)
);


CREATE TABLE comment(
    post INTEGER PRIMARY KEY REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    responding INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT not_same CHECK (post != responding)
);

CREATE TABLE notification(
    id SERIAL PRIMARY KEY,
    content TEXT,
    notification_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    post INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    badge INTEGER REFERENCES badge(id) ON UPDATE CASCADE ON DELETE CASCADE,
        -- CONSTRAINT disjointClasses CHECK ((post IS NULL AND NOT (badge IS NULL)) OR (NOT(post IS NULL) AND badge IS NULL)),
        CONSTRAINT disjointClasses CHECK (post IS NULL OR badge IS NULL),

    -- We can't have a notification with both a badge and a post
	CONSTRAINT date_now CHECK (notification_time <= NOW())
);

CREATE TABLE member_notification(
    member INTEGER REFERENCES member(id) ON UPDATE CASCADE ON DELETE CASCADE,
    notification INTEGER REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY (member, notification)
);

CREATE TABLE report(
    id SERIAL PRIMARY KEY,
    report_date DATE NOT NULL DEFAULT CURRENT_DATE,
    motive TEXT NOT NULL,
    post INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT date_now CHECK (report_date <= NOW())
);

--########################################INDICES#######################################
CREATE INDEX post_date ON post USING btree (publish_date DESC);
CREATE INDEX post_likes ON post USING btree((likes - dislikes) DESC);
CREATE INDEX comment_responding ON comment USING hash (responding);

--FTS
--Posts
ALTER TABLE post
ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS post_search_update();
CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('portuguese', NEW.content), 'A')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.content <> OLD.content) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('portuguese', NEW.content))
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER post_search_update
 BEFORE INSERT OR UPDATE ON post
 FOR EACH ROW
 EXECUTE PROCEDURE post_search_update();

CREATE INDEX post_search_idx ON post USING GIN (tsvectors);

--Question title
ALTER TABLE question
ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS question_search_update();
CREATE FUNCTION question_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('portuguese', NEW.title), 'A')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.title <> OLD.title) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('portuguese', NEW.title))
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER question_search_update
 BEFORE INSERT OR UPDATE ON question
 FOR EACH ROW
 EXECUTE PROCEDURE question_search_update();

CREATE INDEX question_search_idx ON question USING GIN (tsvectors);

--People
ALTER TABLE member
ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS member_search_update();
CREATE FUNCTION member_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('portuguese', NEW.name), 'A') ||
         setweight(to_tsvector('portuguese', NEW.bio), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.name <> OLD.name OR NEW.bio <> OLD.bio) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('portuguese', NEW.name), 'A') ||
         	 setweight(to_tsvector('portuguese', NEW.bio), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER member_search_update
 BEFORE INSERT OR UPDATE ON member
 FOR EACH ROW
 EXECUTE PROCEDURE member_search_update();

CREATE INDEX member_search_idx ON member USING GIN (tsvectors);

--Categories
ALTER TABLE category
ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS category_search_update();
CREATE FUNCTION category_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('portuguese', NEW.name), 'A')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.name <> OLD.name) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('portuguese', NEW.name), 'A')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER category_search_update
 BEFORE INSERT OR UPDATE ON category
 FOR EACH ROW
 EXECUTE PROCEDURE category_search_update();

CREATE INDEX category_search_idx ON category USING GIN (tsvectors);

--########################################TRIGGERS#######################################

-- voting

DROP FUNCTION IF EXISTS upvote();
CREATE FUNCTION upvote() RETURNS TRIGGER AS 
$BODY$
BEGIN
	UPDATE member
	SET likes = likes + NEW.likes
		WHERE id = (select member from post where id = NEW.post);
	IF NEW.likes = 1 THEN
		UPDATE post SET likes = post.likes + 1 WHERE id = NEW.post;
	ELSE
		UPDATE post SET dislikes = post.dislikes + 1 WHERE id = NEW.post;
	END IF;
	RETURN NEW;	
END
$BODY$
LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS remove_vote();
CREATE FUNCTION remove_vote() RETURNS TRIGGER AS 
$BODY$
BEGIN
	IF OlD.likes = 1 THEN
		UPDATE member SET
			likes = member.likes - 1
		WHERE id = (select member from post where id = OLD.post);
		UPDATE post SET
			likes = post.likes -1
		WHERE id = OLD.post;
	ELSE
		UPDATE member SET
			likes = member.likes + 1
		WHERE id = (select member from post where id = OLD.post);
		UPDATE post SET
			dislikes = post.dislikes - 1
		WHERE id = OLD.post;
	END IF;
	RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS switch_vote();
CREATE FUNCTION switch_vote() RETURNS TRIGGER AS 
$BODY$
BEGIN
	IF OLD.likes = 1 THEN
		UPDATE member SET
			member.likes = member.likes - 2
		WHERE id = (select member from post where id = OLD.post);
		UPDATE post SET
			post.likes = post.likes -1,
			post.dislikes = post.dislikes + 1
		WHERE id = OLD.post;
	ELSE
		UPDATE member SET
			member.likes = member.likes + 2
		WHERE id = (select member from post where id = OLD.post);
		UPDATE post SET
			post.likes = post.likes + 1,
			post.dislikes = post.dislikes - 1
		WHERE id = OLD.post;
	END IF;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER upvote
	AFTER INSERT ON likes_member_post
	FOR EACH ROW
	EXECUTE PROCEDURE upvote();
	
CREATE TRIGGER remove_vote
	AFTER DELETE ON likes_member_post
	FOR EACH ROW
	EXECUTE PROCEDURE remove_vote();
	
CREATE TRIGGER switch_vote
	AFTER UPDATE ON likes_member_post
	FOR EACH ROW
	EXECUTE PROCEDURE switch_vote();


-- Check number of likes badge
DROP FUNCTION IF EXISTS verify_badge_likes();
CREATE FUNCTION verify_badge_likes() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF new.likes > old.likes THEN
		IF new.likes >= 1000 THEN
			INSERT INTO member_badge(member, badge) VALUES (new.id, 3)
			ON CONFLICT (member, badge) DO NOTHING;

		ELSIF new.likes >= 100 THEN
			INSERT INTO member_badge(member, badge) VALUES (new.id, 2)
			ON CONFLICT (member, badge) DO NOTHING;

		ELSIF new.likes >= 10 THEN
			INSERT INTO member_badge(member, badge) VALUES (new.id, 1)
			ON CONFLICT (member, badge) DO NOTHING;

		END IF;
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_badge_likes
	AFTER UPDATE 
	ON member
	FOR EACH ROW
	EXECUTE PROCEDURE verify_badge_likes();


-- Check number of questions badge
DROP FUNCTION IF EXISTS create_question();
CREATE FUNCTION create_question() RETURNS TRIGGER AS
$BODY$
DECLARE
question_member INTEGER;
no_question INTEGER;
BEGIN

	SELECT member FROM post WHERE post.id = NEW.post INTO question_member;
	SELECT count(*)
	FROM question 
	JOIN post
	ON question.post= post.id 
	WHERE post.member = question_member
	INTO no_question;
	IF no_question >= 100 THEN
		INSERT INTO member_badge VALUES (question_member, 6)
		ON CONFLICT (member, badge) DO NOTHING;

	ELSIF no_question >= 10 THEN
		INSERT INTO member_badge VALUES (question_member, 5)
		ON CONFLICT (member, badge) DO NOTHING;

	ELSIF no_question >= 1 THEN
		INSERT INTO member_badge VALUES (question_member, 4)
		ON CONFLICT (member, badge) DO NOTHING;
	END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER create_question
	AFTER INSERT ON question
	FOR	EACH ROW
    EXECUTE PROCEDURE create_question();

-- Check number of comments badge

DROP FUNCTION IF EXISTS create_comment();
CREATE FUNCTION create_comment() RETURNS TRIGGER AS
$BODY$
DECLARE
comment_member INTEGER;
no_comment INTEGER;
BEGIN

	SELECT member FROM post WHERE post.id = NEW.post INTO comment_member;
	SELECT count(*)
	FROM comment 
	JOIN post
	ON comment.post= post.id 
	WHERE post.member = comment_member
	INTO no_comment;
	IF no_comment >= 100 THEN
		INSERT INTO member_badge VALUES (comment_member, 9)
			ON CONFLICT (member, badge) DO NOTHING;
	ELSIF no_comment >= 10 THEN
		INSERT INTO member_badge VALUES (comment_member, 8)
			ON CONFLICT (member, badge) DO NOTHING;
	ELSIF no_comment >= 1 THEN
		INSERT INTO member_badge VALUES (comment_member, 7)
			ON CONFLICT (member, badge) DO NOTHING;
	END IF;
            RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER create_comment
	AFTER INSERT ON comment
	FOR	EACH ROW
            EXECUTE PROCEDURE create_comment();


-- Receive a notification when receiving a badge

DROP FUNCTION IF EXISTS insert_badge_create_notification();
CREATE FUNCTION insert_badge_create_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
n_id INTEGER;
BEGIN
	INSERT INTO notification(id, content, notification_time, post, badge) VALUES (DEFAULT, 'You have received a new badge', DEFAULT, NULL, NEW.badge)
		RETURNING id INTO n_id;
	INSERT INTO member_notification(member, notification) VALUES (NEW.member, n_id);
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER insert_badge_create_notification 
	AFTER INSERT ON member_badge
	FOR EACH ROW
	EXECUTE PROCEDURE insert_badge_create_notification();

--Gera notificação para novo post
DROP FUNCTION IF EXISTS new_post();
CREATE FUNCTION new_post() RETURNS TRIGGER AS
$BODY$
BEGIN
        INSERT INTO notification (content, notification_time, post, badge) VALUES ('New Post added', NOW(), NEW.id, NULL);
		RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER new_post_insert
        AFTER INSERT ON post
        FOR EACH ROW
        EXECUTE PROCEDURE new_post();

--notifica os membros com nova notificação
DROP FUNCTION IF EXISTS notify_member();
CREATE FUNCTION notify_member() RETURNS TRIGGER AS
$BODY$
BEGIN
		INSERT INTO member_notification (member, notification) (
		SELECT subscriber, notification
		FROM (
			SELECT notification.id as notification, notification.post, post.member
			FROM notification, post
			WHERE notification.post = post.id and notification.id = new.id
		) AS noti, subscription_member
		WHERE noti.member = subscription_member.subscribed
		);
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;	

CREATE TRIGGER notification_insert
        AFTER INSERT ON notification
        FOR EACH ROW
        EXECUTE PROCEDURE notify_member();


-- Post subscription

DROP FUNCTION IF EXISTS insert_post_subscribe();
CREATE FUNCTION insert_post_subscribe() RETURNS TRIGGER AS
$BODY$
BEGIN
	INSERT INTO subscription_post(member, post) VALUES (NEW.member, NEW.id);
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER insert_post_subscribe
	AFTER INSERT ON post
	FOR EACH ROW
	EXECUTE PROCEDURE insert_post_subscribe();

-- insert_comment_subscribed_post()
DROP FUNCTION IF EXISTS insert_comment_subscribed_post();
CREATE FUNCTION insert_comment_subscribed_post() RETURNS TRIGGER AS
$BODY$
DECLARE
n_id INTEGER;
BEGIN
	INSERT INTO notification(id, content, notification_time, post, badge) VALUES(DEFAULT, 'New comment on a post you subscribe', DEFAULT, NEW.responding, NULL)
		RETURNING id INTO n_id;
		
	INSERT INTO member_notification(member, notification)
	SELECT member, n_id
	FROM (
		SELECT DISTINCT member
		FROM subscription_post
		WHERE post = NEW.responding
	) AS to_notify_members;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER insert_comment_subscribed_post
	AFTER INSERT ON comment
	FOR EACH ROW
	EXECUTE PROCEDURE insert_comment_subscribed_post();

-- insert text notification
DROP FUNCTION IF EXISTS insert_text_notification();
CREATE FUNCTION insert_text_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF NEW.post IS NULL AND NEW.badge IS NULL THEN
		-- Is a global text notification
		INSERT INTO member_notification (member, notification)
		SELECT id, NEW.id
		FROM member;
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER insert_text_notification
	AFTER INSERT ON notification
	FOR EACH ROW
	EXECUTE PROCEDURE insert_text_notification();

-- remove member
DROP FUNCTION IF EXISTS remove_member();
CREATE FUNCTION remove_member() RETURNS TRIGGER AS
$BODY$
DECLARE
likes_from_member record;
BEGIN	
	IF OLD.id = -1 THEN
		raise exception 'CANNOT DELETE';
		-- Prevents deletion of -1 which should always exist for assigning owner ship of posts whose owners were deleted
	END IF;
	
	UPDATE post
		SET member = -1
	WHERE post.member = OLD.id;
	
	FOR likes_from_member IN
		SELECT * FROM likes_member_post WHERE member = OLD.id
	LOOP
		-- Restore likes to the member because the post will be removed and so will the respective likes
		UPDATE member SET
		likes = likes + likes_from_member.likes
		WHERE member.id = (select member from post where post.id = likes_from_member.post);
		
		-- Restore the likes to the post because the removal of the member should keep the likes and dislikes
		IF likes_from_member.likes = 1 THEN
			UPDATE post SET
				likes = likes + 1
			WHERE post.id = likes_from_member.post;
		ELSE
			UPDATE post SET
				dislikes = dislikes + 1
			WHERE post.id = likes_from_member.post;
		END IF;
	END LOOP;
	RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER remove_member 
	BEFORE DELETE ON member
            FOR EACH ROW
            EXECUTE PROCEDURE remove_member();

-- insert_question_subscribed_category
DROP FUNCTION IF EXISTS insert_question_subscribed_category();
CREATE FUNCTION insert_question_subscribed_category() RETURNS TRIGGER AS
$BODY$
DEClARE
n_id integer;
BEGIN
	INSERT INTO notification(id, content, notification_time, post, badge) VALUES(DEFAULT, 'New post on a category you subscribe', DEFAULT, NEW.question, NULL)
		RETURNING id INTO n_id;
		
	INSERT INTO member_notification(member, notification)
	SELECT member, n_id
	FROM (
		SELECT member
		FROM subscription_category
		WHERE category = NEW.category
	) AS to_notify_members;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER insert_question_subscribed_category
	AFTER INSERT OR UPDATE ON question_category
	FOR EACH ROW
	EXECUTE PROCEDURE insert_question_subscribed_category();
