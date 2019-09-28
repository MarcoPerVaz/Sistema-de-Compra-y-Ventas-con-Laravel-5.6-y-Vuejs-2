DELIMITER //
CREATE TRIGGER tr_updStockVentaAnular AFTER UPDATE ON ventas FOR EACH ROW 
BEGIN
  UPDATE articulos a
    JOIN detalle_ventas di
      ON di.idarticulo = a.id
     AND di.idventa= new.id
     set a.stock = a.stock + di.cantidad;
end;
//
DELIMITER ;