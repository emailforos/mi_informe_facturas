<?php
/*
 * This file is part of FacturaScripts
 * Copyright (C) 2013-2016  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'plugins/facturacion_base/model/core/factura_cliente.php';

/**
 * Factura de un cliente.
 * 
 * @author Carlos García Gómez <neorazorx@gmail.com>
 */
class factura_cliente extends FacturaScripts\model\factura_cliente
{
    /* Modificacion para que devuelva solo las series A, XI and XF de ventas reales */
    public function all_desde($desde, $hasta, $codserie = FALSE, $codagente = FALSE, $codcliente = FALSE, $estado = FALSE)
    {
       $faclist = array();
       $sql = "SELECT * FROM ".$this->table_name." WHERE fecha >= ".$this->var2str($desde)." AND fecha <= ".$this->var2str($hasta);
       if($codserie)
       {
            if ($codserie == ""){
                $sql .= " AND codserie = A AND XI AND XF AND Y";/*.$this->var2str($codserie);*/
            } else {
                $sql .= " AND codserie = ".$this->var2str($codserie);
            }
       }
       if($codagente)
       {
          $sql .= " AND codagente = ".$this->var2str($codagente);
       }
       if($codcliente)
       {
          $sql .= " AND codcliente = ".$this->var2str($codcliente);
       }
       if($estado)
       {
          if($estado == 'pagada')
          {
             $sql .= " AND pagada";
          }
          else
          {
             $sql .= " AND pagada = false";
          }
       }
       $sql .= " ORDER BY fecha ASC, codigo ASC;";

       $data = $this->db->select($sql);
       if($data)
       {
          foreach($data as $f)
          {
             $faclist[] = new \factura_cliente($f);
          }
       }

       return $faclist;
    }   
}
