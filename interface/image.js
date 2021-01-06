// DECLARATIONS

const width = 500;

const img = new Image();
img.src = imgSrc;

const randomColor = () => "#" + Math.floor(Math.random()*16777215).toString(16);

const table = document.getElementById("imageBoxes");

let ratio = 1;

const cellVal = (row, k) => row.cells[k].textContent * ratio;


const drawRectangles = (ctx) => {
  for (let i = 1, row; row = table.rows[i]; i++) {
    ctx.beginPath(); 
    ctx.strokeStyle = randomColor();
    ctx.rect(cellVal(row, 1), cellVal(row, 2), cellVal(row, 3), cellVal(row, 4));
    ctx.fillText(row.cells[0].textContent, cellVal(row, 1), cellVal(row, 2)+10);
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
const box = document.getElementById("selbox");
const dot1 = document.getElementById("dot1");
const dot2 = document.getElementById("dot2");

const imgadd = document.getElementById("imgadd"); 
imgadd.src = imgSrc;

window.addEventListener('load', () => {
  const vals_ex = ["imgx_ex", "imgy_ex", "imgw_ex", "imgh_ex"];
  let el_ex = new Object();
  for (const val of vals_ex)
    el_ex[val] = document.getElementById(val);

  const ratio_ex = () => imgadd.clientWidth / imgadd.naturalWidth; // !!!!

  const set_val_ex = (i, val) => (el_ex[ vals_ex[i] ].value = Math.round(val));

  let parity = 1;

  let x, y, x2, y2, xeff, yeff, w, h;

  imgadd.addEventListener('click', (e) => {
    parity = (parity + 1) % 2;
    let rect = e.target.getBoundingClientRect();

    if (parity == 0) {
      y = Math.round(e.clientY - rect.top);
      x = Math.round(e.clientX - rect.left);

      dot1.style.top = `${y}px`;
      dot1.style.left = `${x}px`;

    } else {
      y2 = Math.round(e.clientY - rect.top);
      x2 = Math.round(e.clientX - rect.left);

      dot2.style.top = `${y2}px`;
      dot2.style.left = `${x2}px`;
    }


    if (x > x2) [xeff, w] = [x2, x - x2];
    else [xeff, w] = [x, x2 - x];

    if (y > y2) [yeff, h] = [y2, y - y2];
    else [yeff, h] = [y, y2 - y];

    box.style.left = `${xeff}px`;
    box.style.top = `${yeff}px`;
    box.style.width = `${w}px`;
    box.style.height = `${h}px`;

    set_val_ex(0, xeff / ratio_ex());
    set_val_ex(1, yeff/ ratio_ex());
    set_val_ex(2, w / ratio_ex());
    set_val_ex(3, h / ratio_ex());
  });

});