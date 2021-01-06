<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <title> New dataset</title>

    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>

    <script src=<?= $path."newDataset.js"?> defer></script>

  </head>
  <body>
    <!-- Login panel -->
    <?php include("loginPanel.php");?>

    <!-- Header -->
    <header class="frame">
      <h1> Create new dataset </h1>
    </header>

    <!-- Form to add new dataset -->
    <div class="frame">
      <form action=<?= $path.'actionAddDataset.php'?> method="post">
        <h2> About </h2>
        <input type="text" id="dsName" name="DSNAME" placeholder="Dataset name"><br>
        <input type="text" id="dsDesc" name="DSDESC" placeholder="Description">

        <h2> Data </h2>
        
        <input type="radio" id="radionotall" name="RADIO" value="NOTALL" checked>
        <label for="radionotall">Advanced selection of images</label><br>
        <input type="radio" id="radioall" name="RADIO" value="ALL">
        <label for="radioall">Include all images</label><br>


        <div id="notall">
          <h3> Images </h3>
          <p>
            Fill in id's of images that you want to add to your dataset.
          </p>
          <div id="byimage">
            <input type="text" name="IMGID1" placeholder="Image id"><br>
          </div>
          <a id="byimagebutton"> Add row </a>

          <h3> By classifications </h3>
          <p>
            Add label names and minimal number of their occurances. A system will iteratively add images 
            that contain boxes classified this way (no contradiction). It stops when it runs out
            of images or when the total number of areas exceeds given number (an image can contain
            multiple boxes classified the same way).
          </p>

          <div id="byclass">
              <input type="text" name="LABEL1" placeholder="Label name"> 
              <input type="text" name="COUNT1" placeholder="Number of boxes"><br>
          </div>

          <a id="byclassbutton"> Add row </a>

          <h3> By dataset </h3>
          <p>
            Add images contained in another dataset.
          </p>

          <div id="bydataset">
              <input type="text" name="DS1" placeholder="Dataset id"><br>
          </div>

          <a id="bydatasetbutton"> Add row </a>
        </div>

        <input type="submit" value="Add dataset">

      </form>
    </div>

    <!-- Navigation -->
    <?php include("navigation.php");?>
  </body>
</html>