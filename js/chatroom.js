var initTime = Math.floor(Date.now() / 1000);

var source = new EventSource("/chat/messages");
source.onmessage = function(event) {

    let data = JSON.parse(event.data);
    let result = document.getElementById("chat-list");

    data.forEach(message => {
        result.innerHTML += message.author + " : "+ message.content +"<br>";
    });

    result.scrollTop = result.scrollHeight;
};

source.onerror = function(err) {
    source.close();
    var errorTime = Math.floor(Date.now() / 1000);
    console.error("EventSource failed after", errorTime - initTime, "seconds :", err);
  };

var form = document.querySelector("#live-chatmessage-base");

form.addEventListener("submit", function (event) {

    event.preventDefault();

    let formData = new FormData(this);

    fetch(this.action, {
        method: form.getAttribute("method"),
        body: formData
    })
    .then((response) => response.json())
    .then((data) => {
        if(data.success) {
            clearInput();
        } else {
            alert('Une erreur c\'est produite lors de l\'envoi du message');
        }
    })
    .catch((e) => console.log(e));
});

function clearInput() {
    var input = document.querySelector("#edit-message");
    input.value = "";
}
