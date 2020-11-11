select
  p.id_product as id,
  pl.name,
  pl.description_short,
  pl.description,
  p.price,
  i.id_image as default_image_id,
  c.id_category as default_category_id,
  (
    SELECT
      GROUP_CONCAT(
        agl.name,
        '_',
        al.name
        ORDER BY
          agl.id_attribute_group SEPARATOR '-'
      ) as attribute_designation
    FROM
      ps_product_attribute_combination pac
      LEFT JOIN ps_attribute a ON a.id_attribute = pac.id_attribute
      LEFT JOIN ps_attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
      LEFT JOIN ps_attribute_lang al ON (
        a.id_attribute = al.id_attribute
        AND al.id_lang = 1
      )
      LEFT JOIN ps_attribute_group_lang agl ON (
        ag.id_attribute_group = agl.id_attribute_group
        AND agl.id_lang = 1
      )
    WHERE
      pac.id_product_attribute IN (
        SELECT
          pa.id_product_attribute
        FROM
          ps_product_attribute pa
        WHERE
          pa.id_product = p.id_product
          and default_on = 1
        GROUP BY
          pa.id_product_attribute
      )
    GROUP BY
      pac.id_product_attribute
  ) as combo_default
from
  ps_product p
  inner join ps_product_lang pl on pl.id_product = p.id_product
  and pl.id_lang = 1
  inner join ps_category c on c.id_category = p.id_category_default
  left join ps_image i on i.id_product = p.id_product
  and i.cover = 1
where
  p.active = 1
  and p.id_product = 1;