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


const update = () => {
  ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, width, img.height * ratio);
  drawRectangles(ctx);
};

const c = document.getElementById("canv1");
const ctx = c.getContext("2d");
c.width = width;

img.onload = () => {
  ratio = width / img.width
  c.height = img.height * ratio;
  update();
}

// BUTTON

