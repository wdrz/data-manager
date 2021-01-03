// VARIABLES
const width = 500;
// const imgSrc = 'https://c8.staticflickr.com/1/166/433557810_50fdb9ccc5_o.jpg';

// DECLARATIONS

const img = new Image();
img.src = imgSrc;

const randomColor = () => "#" + Math.floor(Math.random()*16777215).toString(16);

const table = document.getElementById("imageBoxes");

let ratio = 1;

const cellVal = (row, k) => row.cells[k].textContent * ratio;


const drawRectangles = (ctx) => {
  //iterate through rows
  //rows would be accessed using the "row" variable assigned in the for loop
  for (let i = 1, row; row = table.rows[i]; i++) {
    ctx.beginPath(); 
    ctx.strokeStyle = randomColor();
    console.log(ctx.strokeStyle);
    ctx.rect(cellVal(row, 1), cellVal(row, 2), cellVal(row, 3), cellVal(row, 4));
    console.log(cellVal(row, 1), cellVal(row, 2), cellVal(row, 3), cellVal(row, 4));
    ctx.stroke();
  }
};

// UPDATE



const c = document.getElementById("canv1");
const ctx = c.getContext("2d");
c.width = width;

const drawImage = (ct) => {
  ct.drawImage(img, 0, 0, img.width, img.height, 0, 0, width, img.height * ratio);
}

const update = (ct) => {
  drawImage(ct);
  drawRectangles(ct);
};

img.onload = () => {
  ratio = width / img.width
  c.height = img.height * ratio;
  update(ctx);
}


// form to input new areas

const vals = ["imgx", "imgy", "imgw", "imgh"];
let el = new Object();
//vals.forEach(val => el[val] = document.getElementById(val));
for (const val of vals) {
  el[val] = document.getElementById(val);
}
const get_val = (i) => el[ vals[i] ].value * ratio;
const preview = () => {
  update(ctx);
  ctx.beginPath(); 
  ctx.strokeStyle = "red";
  ctx.rect(get_val(0), get_val(1), get_val(2), get_val(3));
  ctx.stroke();
}
for (const val of vals) {
  el[val].addEventListener('change', preview);
}


// popup
/*const vals = ["imgx", "imgy", "imgw", "imgh"];
let el = new Object();
for (const val of vals)
  el[val] = document.getElementById(val);

const get_val = (i) => el[ vals[i] ].value * ratio;
const set_val = (i, val) => (el[ vals[i] ].value = val);

const preview = () => {
  drawImage();
  ctx.beginPath(); 
  ctx.strokeStyle = "red";
  ctx.rect(get_val(0), get_val(1), get_val(2), get_val(3));
  ctx.stroke();
}
for (const val of vals) {
  el[val].addEventListener('change', preview);
}*/
/*
const imgadd = document.getElementById("imgadd"); // canvas 2
const ctx2 = imgadd.getContext("2d");

drawImage(ctx2);
*/
/*
imgadd.addEventListener('click', (e) => {
      let rect = e.target.getBoundingClientRect();
      let x = e.clientX - rect.left; // x position within the element.
      let y = e.clientY - rect.top;  // y position within the element.

    });
*/