insert into Users VALUES (1, 'admin', 'a', 'admin_pass');
insert into Users VALUES (2, 'user1', 'u', 'user1_pass');
insert into Users VALUES (3, 'user2', 'u', 'user2_pass');

insert into Dataset(name,description,date_created,created_by) VALUES ('First random image dataset', 'Subset of Open Images, which is a dataset of 9 million images that have been annotated for image classification, object detection and segmentation, among other modalities. In terms of object detection, 1.9 million images are annotated with bounding boxes spanning 600 classes of objects.', to_date('2021-01-05', 'yyyy-mm-dd'), 1);
insert into Dataset(name,description,date_created,created_by) VALUES ('Second random image dataset', 'Subset of Open Images, which is a dataset of 9 million images that have been annotated for image classification, object detection and segmentation, among other modalities. In terms of object detection, 1.9 million images are annotated with bounding boxes spanning 600 classes of objects.', to_date('2021-01-03', 'yyyy-mm-dd'), 1);
insert into Dataset(name,description,date_created,created_by) VALUES ('Third random image dataset', 'Subset of Open Images, which is a dataset of 9 million images that have been annotated for image classification, object detection and segmentation, among other modalities. In terms of object detection, 1.9 million images are annotated with bounding boxes spanning 600 classes of objects.', to_date('2021-01-01', 'yyyy-mm-dd'), 2);
commit;
