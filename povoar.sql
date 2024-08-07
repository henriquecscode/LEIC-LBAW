SET search_path TO lbaw2162;
/*INSERT INTO member (email, password, name, birth_date, member_type) VALUES ('t', 'tt', 'tiago', '12-10-2020', 'member');
*/

/*BADGES*/
INSERT INTO badge(name) VALUES ('10 likes');
INSERT INTO badge(name)  VALUES ('100 likes');
INSERT INTO badge(name)  VALUES ('1000 likes');
INSERT INTO badge(name) VALUES ('1 questions');
INSERT INTO badge(name)  VALUES ('10 questions');
INSERT INTO badge(name)  VALUES ('100 questions');
INSERT INTO badge(name) VALUES ('1 comments');
INSERT INTO badge(name)  VALUES ('10 comments');
INSERT INTO badge(name)  VALUES ('100 comments');
SELECT * from badge;

/*MEMBER*/
INSERT INTO member (email, password, name, birth_date, member_type)
VALUES ('antoniodias@gmail.com', 'asg124', 'António Dias', '12-08-2000', 'member');

INSERT INTO member (email, password, name, birth_date, member_type)
VALUES ('joanasousa@gmail.com', 'lkjh23', 'Joana Sousa', '01-24-1995', 'member');

INSERT INTO member (email, password, name, birth_date, member_type)
VALUES ('sofiagomes@hotmail.com', 'ggsd45', 'Sofia Gomes', '02-23-2002', 'member');

INSERT INTO member (email, password, name, birth_date, member_type)
VALUES ('lauraamorim@gmail.com', 'jhfdf32', 'Laura Amorim', '12-15-1999', 'mod');

INSERT INTO member (email, password, name, birth_date, member_type)
VALUES ('joaosilva@gmail.com', 'jdtr35', 'João Silva', '02-11-1990', 'mod');

INSERT INTO member (email, password, name, birth_date, member_type)
VALUES ('danielcampos@gmail.com', 'opqwe31', 'Daniel Campos', '06-21-1997', 'admin');

SELECT * FROM MEMBER;

/*SUBSCRIPTION_MEMBER*/
INSERT INTO subscription_member(subscriber, subscribed)
VALUES (2, 3);

INSERT INTO subscription_member(subscriber, subscribed)
VALUES (2, 5);

INSERT INTO subscription_member(subscriber, subscribed)
VALUES (2, 6);

INSERT INTO subscription_member(subscriber, subscribed)
VALUES (4, 1);

INSERT INTO subscription_member(subscriber, subscribed)
VALUES (3, 2);

/*MEMBER_BADGE*/

/*CATEGORY*/
INSERT INTO category (name)
VALUES ('Álgebra');

INSERT INTO category (name)
VALUES ('Geografia');

INSERT INTO category (name)
VALUES ('Biologia');

INSERT INTO category (name)
VALUES ('Geologia');

INSERT INTO category (name)
VALUES ('Java');

INSERT INTO category (name)
VALUES ('Português');

INSERT INTO category (name)
VALUES ('Inglês');

INSERT INTO category (name)
VALUES ('Francês');

INSERT INTO category (name)
VALUES ('Arquitetura de computadores');

INSERT INTO category (name)
VALUES ('Linux');

INSERT INTO category (name)
VALUES ('Windows');

INSERT INTO category (name)
VALUES ('Git');

INSERT INTO category (name)
VALUES ('Anatomia');

INSERT INTO category (name)
VALUES ('Python');

INSERT INTO category (name)
VALUES ('C#');

INSERT INTO category (name)
VALUES ('C++');


/*SUBSCRIPTION_CATEGORY*/
INSERT INTO subscription_category (member, category)
VALUES (2,5);

INSERT INTO subscription_category (member, category)
VALUES (1,4);

INSERT INTO subscription_category (member, category)
VALUES (3,4);

INSERT INTO subscription_category (member, category)
VALUES (4,10);


/*POST*/
INSERT INTO post (content, publish_date, is_edited, edition_date, member)
VALUES ('Quanto é 2+2?', '11-27-2021', FALSE, NULL, 1);

INSERT INTO post (content, publish_date, is_edited, edition_date, member)
VALUES ('2+2=4', '11-28-2021', FALSE, NULL, 2);

/*SUBSCRIPTION_POST*/
INSERT INTO subscription_post (member, post)
VALUES (3,1);


/*LIKES_MEMBER_POST*/
INSERT INTO likes_member_post (member, post, likes)
VALUES (2,1,1);

INSERT INTO likes_member_post (member, post, likes)
VALUES (1,2,1);


/*QUESTION*/
INSERT INTO question (post, is_answered, title)
VALUES (1, TRUE, 'Operação matemática');


/*QUESTION_CATEGORY*/
INSERT INTO question_category (question, category)
VALUES (1,1);


/*COMMENT*/
INSERT INTO comment (post, responding)
VALUES (2,1);


/*NOTIFICATION*/
INSERT INTO notification (content, notification_time, post, badge)
VALUES ('Primeira resposta', '11-28-2021', 1, NULL);


/*MEMBER_NOTIFICATION*/
INSERT INTO member_notification (member, notification)
VALUES (1,1);

/*REPORT*/
INSERT INTO report (report_date, motive, post)
VALUES ('11-28-2021', 'a resposta não explica como chegou lá',2);