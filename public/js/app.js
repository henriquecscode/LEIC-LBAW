function addEventListeners() {
  saveEditedCommentEventListener();
  removeCommentEventListener();
  replyCommentEventListener();
  reportCommentEventListener();
  votingCommentEventListener();
  editModeratorEventListener();
  categoryEventListener();
  orderQuestionsEventListener();
  addCommentEventListener();
  readNotificationEventListener();
  removeReportEventListener();
  addBlockEventListener();
  removeBlockEventListener();
  addBlockProfileEventListener()
  removeBlockProfileEventListener();
}

function saveEditedCommentEventListener() {
  let commentSaveEditedButtons = document.querySelectorAll('a.comment-edit-button');
  [].forEach.call(commentSaveEditedButtons, function (button) {
    button.addEventListener('click', saveEditedComment);
  });
}

function removeCommentEventListener() {
  let commentRemoveButtons = document.querySelectorAll('a.comment-remove-button');
  [].forEach.call(commentRemoveButtons, function (button) {
    button.addEventListener('click', removeComment);
  });
}

function replyCommentEventListener() {
  let commentPostButtons = document.querySelectorAll('a.comment-reply-button');
  [].forEach.call(commentPostButtons, function (button) {
    button.addEventListener('click', replyComment);
  });
}

function reportCommentEventListener() {
  let commentReportButtons = document.querySelectorAll('a.post-report-button');
  [].forEach.call(commentReportButtons, function (button) {
    button.addEventListener('click', reportPost);
  });
}

function votingCommentEventListener() {
  let commentUpvoteButtons = document.querySelectorAll('a.post-upvote-button');
  [].forEach.call(commentUpvoteButtons, function (button) {
    button.addEventListener('click', upvotePost);
  });

  let commentDownvoteButtons = document.querySelectorAll('a.post-downvote-button');
  [].forEach.call(commentDownvoteButtons, function (button) {
    button.addEventListener('click', downvotePost);
  });
}

function editModeratorEventListener() {
  let addMod = document.getElementById('addMod');
  if (addMod != null) {
    addMod.addEventListener('click', addModerator);
  }

  let removeMod = document.getElementById('removeMod');
  if (removeMod != null) {
    removeMod.addEventListener('click', removeModerator);
  }
}
function categoryEventListener() {
  let category = document.getElementById('category-questions');
  if (category == null) {
    return;
  }
  let id = category.getAttribute('category-id');

  let byTime = document.getElementById('category-questions-by-time');
  byTime.addEventListener('click', sortCategoryQuestionsByTime.bind(null, id));

  let byVotes = document.getElementById('category-questions-by-votes');
  byVotes.addEventListener('click', sortCategoryQuestionsByVotes.bind(null, id));

  let byNotAnswered = document.getElementById('category-questions-by-not-answered');
  byNotAnswered.addEventListener('click', sortCategoryQuestionsByNotAnswered.bind(null, id));

  let search = document.getElementById('category-search');
  search.addEventListener('click', searchCategory.bind(null, id));
}

function orderQuestionsEventListener() {
  // Edit with element of the page that has the sorting of the questions
  let category = document.getElementById('category-questions');
  if (category == null) {
    return;
  }

  let byTime = document.getElementById('questions-by-time');
  if (byTime != null) {
    byTime.addEventListener('click', sortQuestionsByTime);
  }

  let byVotes = document.getElementById('questions-by-votes');
  if (byVotes != null) {
    byVotes.addEventListener('click', sortQuestionsByTime);
  }
  let byNotAnswered = document.getElementById('questions-by-not-answered');
  if (byNotAnswered != null) {
    byNotAnswered.addEventListener('click', sortQuestionsByTime);
  }
}

function addCommentEventListener() {
  let answer = document.getElementById("post-comment");
  if (answer != null) {
    answer.addEventListener('click', postComment);
  }
}


function readNotificationEventListener() {
  let markAsReadButtons = document.querySelectorAll('a.mark-notification-read');
  [].forEach.call(markAsReadButtons, function (button) {
    button.addEventListener('click', markAsRead);
  });

  let notificationDivs = document.querySelectorAll('div[notification-id]');
  [].forEach.call(notificationDivs, function (div) {
    let markAsReadButton = div.querySelector('a.mark-notification-read');
    if (markAsReadButton == null) { return; } // A notification from the notification page that is already read
    let clickable = div.querySelector('a[href]');
    if (clickable == null) { return; } // A text notification
    clickable.addEventListener('click', markAsRead);
  })
}

function removeReportEventListener() {
  let reportRemoveButtons = document.querySelectorAll('a.report-remove-button');
  [].forEach.call(reportRemoveButtons, function (button) {
    button.addEventListener('click', removeReport);
  });
}

function addBlockEventListener(){
  let blockAddButtons = document.querySelectorAll('a.block-add-button');
  [].forEach.call(blockAddButtons, function (button) {
    button.addEventListener('click', addBlock);
  });
}

function removeBlockEventListener(){
  let blockRemoveButtons = document.querySelectorAll('a.block-remove-button');
  [].forEach.call(blockRemoveButtons, function (button) {
    button.addEventListener('click', removeBlock);
  });
}

function addBlockProfileEventListener(){
  let profileBlockButton = document.querySelectorAll('a.profile-block-button');
  [].forEach.call(profileBlockButton, function (button) {
    button.addEventListener('click', addBlockProfile);
  });
}

function removeBlockProfileEventListener(){
  let profileUnblockButton = document.querySelectorAll('a.profile-unblock-button');
  [].forEach.call(profileUnblockButton, function (button) {
    button.addEventListener('click', removeBlockProfile);
  });
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function (k) {
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function saveEditedComment() {
  let comment_div = this.closest('div');
  let comment = comment_div.children[0];
  let id = comment.getAttribute('comment-id');
  let content = comment.value;
  comment.setAttribute('edited', true);
  sendAjaxRequest('post', '/comment/edit/' + id, { conteudo: content }, commentUpdateHandler);
}

function commentUpdateHandler() {

  let comment = JSON.parse(this.responseText);
  let element = document.getElementById("comment-" + comment.id);
  let textarea = document.querySelector('textarea[comment-id="' + comment.id + '"]');
  textarea.removeAttribute('edited');
  element.innerHTML = comment.content;
  textarea.value = comment.content;
  cancelEdit(comment.id);
}

function removeComment() {
  let comment_div = this.closest('div');
  let id = comment_div.getAttribute('comment-id');
  comment_div.setAttribute("toRemove", id);
  sendAjaxRequest('post', '/comment/remove/' + id, {}, removeCommentHandler);
}

function removeCommentHandler() {
  console.log(this);
  console.log(this.responseText);
  let toInsert = createElementFromHTML(this.responseText);
  let id = toInsert.getAttribute("comment-id");
  let toRemove = document.querySelector("div[toRemove='" + id + "']");
  if (toRemove == null) {
    return;
  }
  toRemove.parentNode.replaceChild(toInsert, toRemove);
  addEventListeners();
}

function replyComment() {
  let textarea = this.closest("div").querySelector('textarea');
  let id = textarea.getAttribute('comment-id');
  let value = textarea.value;
  if (value == "") {
    return;
  }
  console.log(value, id);
  sendAjaxRequest('post', '/comment/create/' + id, { conteudo: value }, (e) => { replyCommentHandler.call(e.target, id) });

}

function replyCommentHandler(id) {
  console.log(this);
  if (this.status == 200) {
    let commentReplyDiv = document.getElementById('comment-reply-' + id);
    commentReplyDiv.children[0].value = "";
    let commentsDiv = document.getElementById('comment-' + id + '-comments');
    commentsDiv.innerHTML = this.responseText + commentsDiv.innerHTML;
    cancelReply(id);
    addEventListeners();
  }
  else if (this.status == 401) {
    window.location.href = this.responseText;
  }
}

function reportPost() {
  let textarea = this.closest("div").querySelector('textarea');
  let id = textarea.getAttribute('post-id');
  let value = textarea.value;
  if (value == "") {
    return;
  }
  sendAjaxRequest('post', '/post/' + id + '/report', { conteudo: value }, (e) => { reportPostHandler.call(e.target, id) });

}

function reportPostHandler(id) {
  console.log(this);
  if (this.status == 200) {
    let reportDiv = document.getElementById('post-report-' + id);
    reportDiv.children[0].value = "";
    let timeoutElement = document.createElement('div');
    timeoutElement.innerHTML = this.responseText;
    timeoutElement.style = "color: red";
    reportDiv.nextElementSibling.insertAdjacentElement("afterend", timeoutElement);
    setTimeout(() => {
      timeoutElement.remove();
    }, 5000);
    cancelReport(id);
  }
  else if (this.status == 401) {
    window.location.href = this.responseText;
  }
  else if (this.status == 404) {
    let reportDiv = document.getElementById('post-report-' + id);
    reportDiv.children[0].value = "";
    cancelReport(id);
  }
}



function upvotePost() {
  let id = this.closest("div").getAttribute("post-id");
  let value = 1;
  if (this.classList.contains("button-outline")) {
    value = 0;
  }
  console.log(id);
  sendAjaxRequest('post', '/post/' + id + '/vote/' + value, { conteudo: value }, (e) => { votePostHandler.call(e.target, value) });
}

function downvotePost() {
  let id = this.closest("div").getAttribute("post-id");
  let value = -1;
  if (this.classList.contains("button-outline")) {
    value = 0;
  }
  console.log(id);
  sendAjaxRequest('post', '/post/' + id + '/vote/' + value, { conteudo: value }, (e) => { votePostHandler.call(e.target, value) });
}

function votePostHandler(value) {
  console.log(this);
  console.log('value', value);
  if (this.status == 200) {
    let post = JSON.parse(this.responseText);
    console.log(post);
    let votesDiv = document.getElementById('post-votes-' + post.id);
    console.log('votes div', votesDiv);
    let votesNoVotesDiv = votesDiv.children[0];
    votesNoVotesDiv.innerHTML = post.likes - post.dislikes
    if (value == -1) {
      votePostClick(votesDiv, 0, 1);
    }
    else if (value == 0) {
      votePostClick(votesDiv, 0, 0);
    }
    else if (value == 1) {
      votePostClick(votesDiv, 1, 0);
    }
    addEventListeners();
  }
  else if (this.status == 401) {
    window.location.href = this.responseText;
  }
  if (this.status == 404) {
    // Do nothing
  }
}

function votePostClick(postDiv, isUpvoteClicked, isDownvoteClicked) {
  console.log('postt vote', postDiv, isUpvoteClicked, isDownvoteClicked);
  upvote = postDiv.children[1];
  console.log(upvote);
  if (isUpvoteClicked) {
    upvote.classList.add("button-outline");
  }
  else {
    upvote.classList.remove("button-outline");
  }

  downvote = postDiv.children[2];
  console.log(downvote);
  if (isDownvoteClicked) {
    downvote.classList.add("button-outline");
  }
  else {
    downvote.classList.remove("button-outline");
  }
}

function addModerator() {
  let id = this.getAttribute('member-id');
  sendAjaxRequest('post', '/moderators/add_moderator/' + id, {}, addModeratorHandler);
}

function addModeratorHandler() {
  let comment = JSON.parse(this.responseText);
  moderatorViewButtons(comment.member_type);
}

function removeModerator() {
  let id = this.getAttribute('member-id');
  sendAjaxRequest('post', '/moderators/remove_moderator/' + id, {}, removeModeratorHandler);
}

function removeModeratorHandler() {
  let comment = JSON.parse(this.responseText);
  moderatorViewButtons(comment.member_type);
}

function moderatorViewButtons(type) {
  let addMod = document.getElementById('addMod');
  let removeMod = document.getElementById('removeMod');
  let viewMod = document.getElementById('member-type');
  if (type == 'mod') {
    addMod.style.display = "none";
    removeMod.style.display = "";
  }
  else {
    addMod.style.display = "";
    removeMod.style.display = "none";
  }
  viewMod.innerHTML = '(' + type + ')'
}

function postComment() {
  let id = this.getAttribute('responding-id');
  let textarea = this.closest("div").querySelector('textarea');
  let value = textarea.value;
  if (value == "") {
    return;
  }
  sendAjaxRequest('post', '/comment/create/' + id, { conteudo: value }, (e) => { postCommentHandler.call(e.target, id) });
}

function postCommentHandler(id) {
  let commentsDiv = document.getElementById('comment-' + id + '-comments');
  let postReply = document.getElementById("post-reply");
  postReply.children[1].value = "";
  commentsDiv.innerHTML = this.responseText + commentsDiv.innerHTML;
  window.scrollTo(0, 0);
  addEventListeners();
}

function sortCategoryQuestionsByTime(id) {
  sendAjaxRequest('get', '/questions/category/' + id + '/byTime', {}, CategoryQuestionsHandler);

}

function sortCategoryQuestionsByVotes(id) {
  sendAjaxRequest('get', '/questions/category/' + id + '/byVotes', {}, CategoryQuestionsHandler);
}

function sortCategoryQuestionsByNotAnswered(id) {
  sendAjaxRequest('get', '/questions/category/' + id + '/byNotAnswered', {}, CategoryQuestionsHandler);
}

function searchCategory(id) {

  let button = document.getElementById('category-search');
  let value = button.closest('div').children[0].value;
  if (value == "") {
    return;
  }
  sendAjaxRequest('get', '/questions/category/' + id + '/search' + '?search=' + value + '&', {}, CategoryQuestionsHandler);

}

function CategoryQuestionsHandler() {
  if (this.status != 200) {
    return;
  }
  console.log(this);
  let list = document.getElementById('category-questions')
  list.innerHTML = this.responseText;

  if (list.children[0].children.length == 0) {
    list.innerHTML = "No questions found";
  }

}

function sortQuestionsByTime() {
  sendAjaxRequest('get', '/questions/byTime', {}, sortQuestionsHandler);
}

function sortQuestionsByVotes() {
  sendAjaxRequest('get', '/questions/byVotes', {}, sortQuestionsHandler);
}

function sortQuestionsByNotAnswered() {
  sendAjaxRequest('get', '/questions/byNotAnswered', {}, sortQuestionsHandler);
}

function sortQuestionsHandler() {
  if (this.status != 200) {
    return;
  }
  console.log(this);
  let list = document.getElementById('category-questions')
  list.innerHTML = this.responseText;

  if (list.children[0].children.length == 0) {
    list.innerHTML = "No questions found";
  }

}

function markAsRead() {
  let id = this.closest('div[notification-id]').getAttribute('notification-id');
  console.log(id);
  console.log('/self/notification/read/' + id);
  sendAjaxRequest('post', '/self/notifications/read/' + id, {}, (e) => { markAsReadHandler.call(e.target, id) });
}

function markAsReadHandler(id) {
  if (this.status != 200) { return; }

  // Get possible pop-up notification
  let popUpDiv = document.querySelector("div.notification[popup][notification-id='" + id + "']");
  if (popUpDiv != null) {
    popUpDiv.remove();
  }

  // Get possible notification in notifications page
  let notificationDiv = document.querySelector("div.notification:not([popup])[notification-id='" + id + "']");
  if (notificationDiv != null) {
    let newNotification = createElementFromHTML(this.responseText);
    notificationDiv.parentNode.replaceChild(newNotification, notificationDiv);
  }

}

function removeReport() {
  
  let id = this.getAttribute('report-id');
  console.log(id);
  sendAjaxRequest('post', '/reports/removeReport/' + id, {}, (e) => { removeReportHandler.call(e.target, id) });

}

function removeReportHandler(id) {
  
  if(this.status!='200'){
    return;
  }
  let div = document.getElementById("report-"+id);
  div.remove();
}

function addBlock() {

  let id = this.getAttribute('user-id-block');
  sendAjaxRequest('post', '/blocks/members/' + id , {}, (e) => { addBlockHandler.call(e.target, id) });
}

function addBlockHandler(id) {

  let div = document.getElementById("user-"+id);
  div.remove();
}

function removeBlock() {
  let id = this.getAttribute('user-id-unblock');
  //id is blocked member's id
  sendAjaxRequest('post', '/unblocks/members/' + id , {}, (e) => { removeBlockHandler.call(e.target, id) });
}


function removeBlockHandler(id) {
  let div = document.getElementById("blocked-user-"+id);
  div.remove();
}

function addBlockProfile(){
 let id = this.getAttribute('profile-id-block');;
 sendAjaxRequest('post', '/blocks/members/' + id , {}, (e) => { addBlockProfileHandler.call(e.target, id) });
}

function addBlockProfileHandler(){
  let div = document.getElementById("blockblock");
  if(this.status!=200){
    return;
  }
  let newDiv = createElementFromHTML(this.responseText);
  div.parentNode.replaceChild(newDiv,div);
  addEventListeners();
}

function removeBlockProfile(){
  let id = this.getAttribute('profile-id-unblock');
  sendAjaxRequest('post', '/unblocks/members/' + id , {}, (e) => { removeBlockProfileHandler.call(e.target, id) });
}

function removeBlockProfileHandler(){
  let div = document.getElementById("blockblock");
  if(this.status!=200){
    return;
  }
  let newDiv = createElementFromHTML(this.responseText);
  div.parentNode.replaceChild(newDiv,div);
  addEventListeners();
}


function blockedProfileSwitch(blocked) {

  var blocked = document.getElementById("blockedUser");
  var unblocked = document.getElementById("unblockedUser");
  
  if(blocked){
    blocked.style.display = "block";
    unblocked.style.display = "none";
  }
  else{
    unblocked.style.display = "block";
    blocked.style.display = "none";
  }
}


function showEdit(id) {
  comment = document.getElementById('comment-' + id);
  comment_edit = document.getElementById('comment-edit-' + id);
  comment.style.display = "none";
  comment_edit.style.display = "block";
}
function cancelEdit(id) {
  comment = document.getElementById('comment-' + id);
  comment_edit = document.getElementById('comment-edit-' + id);
  comment.style.display = "initial";
  comment_edit.style.display = "none";
}


function showReply(id) {
  comment_reply = document.getElementById('comment-reply-' + id);
  comment_reply.style.display = "block";
}
function cancelReply(id) {
  comment_reply = document.getElementById('comment-reply-' + id);
  comment_reply.style.display = "none";
}

function showReport(id) {
  post_report = document.getElementById('post-report-' + id);
  post_report.style.display = "block";
}
function cancelReport(id) {
  post_report = document.getElementById('post-report-' + id);
  post_report.style.display = "none";
}

function showDeleteAccout() {
  delete_account = document.getElementById('delete-account-div');
  delete_account.style.display = "block";
}

function cancelDeleteAccount() {
  delete_account = document.getElementById('delete-account-div');
  delete_account.style.display = "none";
}



document.addEventListener('DOMContentLoaded', function () {
  addEventListeners();
});

// https://stackoverflow.com/a/494348
function createElementFromHTML(htmlString) {
  var div = document.createElement('div');
  div.innerHTML = htmlString.trim();

  // Change this to div.childNodes to support multiple top-level nodes
  return div.firstChild;
}