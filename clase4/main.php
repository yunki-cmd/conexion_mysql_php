<?php

   include_once './mysql.php';

    $obMysql = new mysql();
    /* $obMysql->exceStrQueryOB($obMysql->tabla); */
    /* $obMysql->exceStrQueryPDO($obMysql->strInsert); */
    $obMysql ->insertPro();
?>