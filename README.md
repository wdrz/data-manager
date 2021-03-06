# DB course project
## Introduction
This application is an image dataset manager. Its purpose is to upload, delete, display and download image datasets, as well as to insert and review classifications. The project in combination of PHP, JavaScript, HTML, CSS, PL/SQL. Python was used to generate sample data in a form of a .sql script. Last but not least, the project's database is Oracle.

## Model
Database contains user credentials, labels, datasets, images, areas of images and classiffications of areas. Users have different roles in the system: ordinary users and admins. Different groups have different permissions to perform operations such as viewing, modifying, createing datasets, specifying new areas of existing images, adding images, classifying areas of images.

![Image 0](images/uml.png)

It is ensured by PL/SQL triggers that the graph of labels is a forest, ie. there are no cycles. It is performed by associatiang a depth with every label and a simple check if such depth is greater than the depth of label's parent by 1.

![Image triggers](images/triggers.png)

System also can detect contradicting classifications of image areas. Two classifications are said to be contradictory if they are not the same and any one of them is not a generalization of the other (in the graph of labels, one does not lay on the path to the root from other and vice versa). If such two contradictory classifications exist, an area of image is properly marked, and its subpage changes style to more reddish. This functionality is again written in PL/SQL. Check out this code [here](model/constraints.sql).

## Interface
Front page contains a list of existing datasets with their titles, descriptions etc. It also allows to jump to subpages and to login. A login panel is displayed on every page.

![Image 1](images/1.png)

From front page one can jump to page which contains a list of images. The list is paginated. By clicking a big '+' sign, user can open a popup (see image below) (JS, CSS) which contains a form that allows to insert an url to image on the internet that will be added to the database. Such image can be later linked to one or more datasets, by visiting a dataset subpage and filling in a form there.

![Image 2](images/2.png)

There is a subpage that displays a details of selected image from database. A HTML canvas element was used to draw boxes on top of the image to visually represent areas of the image.

![Image 3](images/3.png)

A special interactive tool (JS, CSS) allows user to select a custom area. Click anywhere on the image to select the top-left corner and then the bottom-right corner. Correct values will appear in the form, which a user can submit to add selected box to database.

![Image 4](images/4.png)

Boxes can be classified by users. Everyone can see a table that contains a list of classifications. Each user can add such classification. A classification must be a string which has been added to Label table. A list of labels can be browsed through its own specific subpage. Admins can also delete classifications of other users.

![Image 5](images/5.png)

Each dataset can be downloaded (.csv).

![Image 6](images/6.png)

## Data
This project uses Oracle database.

### Source
Database contains 100 images divided into 3 datasets.  
This sample data is adapted from __Open Images Dataset__:

<g.co/dataset/open-images>

More specifically, I have downloaded following data
```
classes_2017_11.tar.gz
https://storage.googleapis.com/openimages/2017_11/classes_2017_11.tar.gz

annotations_human_bbox_2017_11.tar.gz
https://storage.googleapis.com/openimages/2017_11/annotations_human_bbox_2017_11.tar.gz

images_2017_11.tar.gz
https://storage.googleapis.com/openimages/2017_11/images_2017_11.tar.gz
```

I have adapted this data using [this notebook](data/dataformatting.ipynb).
The same notebook can be used to adapt pieces of original dataset.

### Generated files
File `create.sql` contains desription of tables, `combined_data.sql` contains sample data, origin oof which I described before.

---

2020-2021 Faculty of Mathematics, Informatics, and Mechanics, University of Warsaw
