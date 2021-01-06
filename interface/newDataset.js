const notall = document.getElementById("notall");
const byimage = document.getElementById("byimage");
const byclass = document.getElementById("byclass");
const bydataset = document.getElementById("bydataset");

let im = 1;
let cl = 1;
let ds = 1;

document.getElementById("radioall").addEventListener('change', () => {
  notall.style.display = "none";
});

document.getElementById("radionotall").addEventListener('change', () => {
  notall.style.display = "block";
});

document.getElementById("byimagebutton").addEventListener('click', () => {
  byimage.innerHTML += `
    <input type="text" name="IMGID${++im}" placeholder="Image id"><br>
  `;
});


document.getElementById("byclassbutton").addEventListener('click', () => {
  byclass.innerHTML += `
    <input type="text" name="LABEL${++cl}" placeholder="Label name"> 
    <input type="text" name="COUNT${cl}" placeholder="Number of boxes"><br>
  `;
});

document.getElementById("bydatasetbutton").addEventListener('click', () => {
  bydataset.innerHTML += `
    <input type="text" name="DS${++ds}" placeholder="Dataset id"><br>
  `;
});