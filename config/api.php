<?php

return [
  'resources' => [
    'products' => [
      'query' => [
        'selectClause' => 'SELECT p.id_product, pl.name, pl.description_short, pl.description,p.price,i.id_image, pad.id_product_attribute',
        'fromClause' => 'FROM ps_product p
          							INNER JOIN ps_product_lang pl ON pl.id_product = p.id_product
          							INNER JOIN ps_category_lang cl ON cl.id_category = p.id_category_default AND cl.id_lang = pl.id_lang
          							LEFT JOIN ps_product_attribute pad ON pad.id_product = p.id_product AND pad.default_on = 1
          							LEFT JOIN ps_image i ON i.id_product = p.id_product AND i.cover = 1',
        'whereClause' => 'WHERE p.active = 1 AND pl.id_lang = 1',
        'groupClause' =>'GROUP BY p.id_product',
        'orderClause' => ''
      ],
      'with' => [
				'combinations' => [
          'selectClause' => 'GROUP_CONCAT(distinct pa.id_product_attribute order by pa.id_product_attribute) AS WithCombos',
					'fromClause' => 'LEFT JOIN ps_product_attribute pa ON pa.id_product = p.id_product',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
				],
				'categories' => [
					'selectClause' => 'GROUP_CONCAT(distinct cp.id_category order by cp.id_category) AS WithCategories',
					'fromClause' => 'LEFT JOIN ps_category_product cp ON cp.id_product = p.id_product',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
				],
				'images' => [
					'selectClause' => 'GROUP_CONCAT(distinct ie.id_image order by ie.id_image) AS WithImages',
					'fromClause' => 'LEFT JOIN ps_image ie ON ie.id_product = p.id_product',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
				]
      ]
    ],
		'categories' => [
			'query' => [
				'selectClause' => 'SELECT c.id_category, cl.name, cl.description, c.level_depth  ',
        'fromClause' => 'FROM ps_category c
                        INNER JOIN ps_category_lang cl ON cl.id_category = c.id_category',
        'whereClause' => 'WHERE cl.id_lang = 1',
        'groupClause' => 'GROUP BY c.id_category',
        'orderClause' => 'order by level_depth, id_category'
			],
			'with' => [
				'products' => [
					'selectClause' => 'GROUP_CONCAT(cp.id_product) AS id_products',
					'fromClause' => 'LEFT JOIN ps_category_product cp ON cp.id_category = c.id_category',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
				],
				'parent' => [
					'selectClause' => 'c.id_parent',
          'fromClause' => '',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
				],
        'childs' => [
          'selectClause' => ' GROUP_CONCAT(distinct ch.id_category) AS id_child',
          'fromClause' => 'left join ps_category ch on ch.id_parent = c.id_category',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
        ]
			]
		],
		'customers' => [
			'query' => [
				'selectClause' => 'SELECT id_customer, firstname, lastname, email',
        'fromClause' => 'FROM ps_customer c',
        'whereClause' => '',
        'groupClause' => '',
        'orderClause' => ''
			],
      'with' => []
		],
		'images' => [
			'query' => [
				'selectClause' => 'SELECT distinct p.id_product, i.id_image,color AS codigo,  psatrl.name AS color',
        'fromClause' => 'FROM ps_product p
					INNER JOIN ps_image i ON p.id_product = i.id_product
					INNER JOIN ps_product_attribute atr ON atr.id_product = p.id_product
					INNER JOIN ps_product_attribute_image atrimg ON atrimg.id_product_attribute = atr.id_product_attribute AND atrimg.id_image = i.id_image
					INNER JOIN ps_product_attribute_combination com ON com.id_product_attribute = atr.id_product_attribute
					INNER JOIN ps_attribute psatr ON psatr.id_attribute = com.id_attribute
					INNER JOIN ps_attribute_lang psatrl ON psatr.id_attribute = psatrl.id_attribute
					INNER JOIN ps_attribute_group ag ON psatr.id_attribute_group = ag.id_attribute_group',
        'whereClause' => 'WHERE ag.is_color_group = 1 AND psatrl.id_lang =1',
        'groupClause' => '',
        'orderClause' => ''
			],
      'with' => []
		],
		'combinations' => [
			'query' => [
				'selectClause' => 'SELECT pac.id_product_attribute, pa.id_product, sum(pa.price) AS price, pai.id_image,
					(SELECT SUM(quantity) FROM ps_stock_available WHERE id_product_attribute = pac.id_product_attribute) AS stock,
					GROUP_CONCAT(agl.name, "_",al.name ORDER BY agl.id_attribute_group SEPARATOR "-") AS combo,
					GROUP_CONCAT(al.id_attribute) AS id_attributes_combo',
        'fromClause' => 'FROM ps_product_attribute_combination pac
					LEFT JOIN ps_attribute a ON a.id_attribute = pac.id_attribute
					LEFT JOIN ps_attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
					LEFT JOIN ps_attribute_lang al ON a.id_attribute = al.id_attribute
					LEFT JOIN ps_attribute_group_lang agl ON ag.id_attribute_group = agl.id_attribute_group AND agl.id_lang = al.id_lang
					LEFT JOIN ps_product_attribute_image pai ON pai.id_product_attribute = pac.id_product_attribute
					INNER JOIN ps_product_attribute pa ON pa.id_product_attribute = pac.id_product_attribute',
        'whereClause' => 'WHERE al.id_lang = 1',
				'groupClause' => 'GROUP BY pac.id_product_attribute, pai.id_image',
        'orderClause' => ''
			],
      'with' => []
		],
		'carts' => [
			'query' => [
				'selectClause' => 'SELECT c.id_cart, c.id_customer, CASE WHEN o.id_order IS NULL THEN 0 ELSE 1 END AS active',
        'fromClause' => 'FROM ps_cart c
					LEFT JOIN ps_orders o ON o.id_cart = c.id_cart',
        'whereClause' => '',
        'groupClause' => 'GROUP BY id_cart,id_customer',
        'orderClause' => ''
			],
			'with' => [
				'products' => [
					'selectClause' => 'GROUP_CONCAT(cp.id_product, "_" , cp.quantity) AS id_products ',
					'fromClause' => 'INNER JOIN ps_cart_product cp ON cp.id_cart = c.id_cart',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
				],
				'promos' => [
					'selectClause' => 'GROUP_CONCAT(ccr.id_cart_rule) AS id_promos ',
					'fromClause' => 'LEFT JOIN ps_cart_cart_rule ccr ON ccr.id_cart = c.id_cart',
          'whereClause' => '',
          'groupClause' => '',
          'orderClause' => ''
				]
			]
		],
		'promos' => [
			'query' => [
				'selectClause' => 'SELECT id_cart_rule, code,
					CASE WHEN reduction_percent != 0 THEN reduction_percent ELSE reduction_amount END AS value,
					CASE WHEN reduction_percent != 0 THEN "percentage" ELSE "fixed" END AS type,
					date_from, date_to, description, free_shipping, active',
        'fromClause' => 'FROM ps_cart_rule',
        'whereClause' => '',
        'groupClause' => '',
        'orderClause' => ''
			],
      'with' => []
		]
  ]
];
