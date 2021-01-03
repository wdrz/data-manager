/*
  you should add following components:

  <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."popup.css"?>>

  <a id="openPopup"> Open window </a>
  
  <div class="message">
    ...
    <a id="closePopup">close</a>
  </div>
  
*/

let komunikat = document.querySelector(".message");

function message() {
    /* Adds shadow */
    const shadow = document.createElement("div");
    shadow.setAttribute("class", "shadow");
    document.body.appendChild(shadow);

    /* Displays message */
    komunikat.style.display="inline";
}

/* Hides message & shadow */
function closeP() {
    komunikat.style.display="none";
    const shadow = document.querySelector(".shadow");
    shadow.remove();
}
document.getElementById("closePopup").addEventListener("click", (e) => {
  closeP();
});

document.getElementById("openPopup").addEventListener("click", (e) => {
  message();
});