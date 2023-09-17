SELECT i.name, ti.stock_physic, ti.stock_system, ti.differential, ti.info,  i.inc_id, ti.inc_id as id_detail, i.category_id
                FROM stockopname_detail ti
                LEFT  JOIN items i ON ti.items_id = i.inc_id 
                LEFT JOIN category c ON i.category_id = c.id
                WHERE ti.stockopname_id = 2 AND i.category_id = 6 
                GROUP BY ti.inc_id