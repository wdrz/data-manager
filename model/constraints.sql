
create or replace function check_contradiction(area_id_param NUMBER) return number is
  retval NUMBER;
begin
  with MaxDepth (a) as (
    select
        LABEL_ID
    from
        (select
          LABEL_ID, NAME,
          row_number() over (order by depth desc) as row_num
        from
          (select * from Classification natural join Label)
        where
          area_id = area_id_param)
    where row_num = 1
  ) select count(*) into retval from (
      (select label_id from Classification where area_id = area_id_param)
    minus
      (select label_id from Label
        start with label_id = (select a from MaxDepth)
        CONNECT BY PRIOR parent_id = label_id)
  );
  return retval;
end;
/

CREATE OR REPLACE PROCEDURE mark_contradiction_all IS
  CURSOR boxes IS (SELECT * FROM IMAGEAREA WHERE CONTRADICTORY_LABELS = 2
      ) FOR UPDATE OF CONTRADICTORY_LABELS;
BEGIN
  FOR box IN boxes LOOP
    UPDATE
        IMAGEAREA
    SET
        CONTRADICTORY_LABELS = case when check_contradiction(area_id) = 0 then 0 else 1 end
    WHERE
        area_id = box.area_id;
  END LOOP;
END;
/

CREATE OR REPLACE TRIGGER contradictory_classifications
BEFORE INSERT OR DELETE ON Classification
for each row
BEGIN
  IF INSERTING THEN
  update ImageArea
     set CONTRADICTORY_LABELS = 2 where area_id = :new.AREA_ID;
  END IF;

  IF DELETING THEN
        update ImageArea
    set CONTRADICTORY_LABELS = 2 where area_id = :old.AREA_ID;
  end if;
END;
/

CREATE OR REPLACE TRIGGER contradictory_class_after
AFTER INSERT OR DELETE ON Classification
BEGIN
    mark_contradiction_all();
END;
/

CREATE OR REPLACE TRIGGER depths_match
BEFORE INSERT ON Label
FOR EACH ROW
WHEN (new.parent_id is not null)
DECLARE
    val NUMBER;

BEGIN
    select depth into val from Label where label_id = :new.PARENT_ID;
    :new.DEPTH := val + 1;
END;
/