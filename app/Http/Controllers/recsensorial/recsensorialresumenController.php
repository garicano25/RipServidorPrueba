<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

// Modelos
use App\modelos\recsensorial\recsensorialModel;

class recsensorialresumenController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialresumentabla($recsensorial_id)
    {
        try
        {
            $sql = DB::select('SELECT
                                    recsensorialpruebas.recsensorial_id,
                                    cat_prueba.catPrueba_Tipo,
                                    TABLA1.catprueba_id,
                                    IF(IFNULL(TABLA1.agente_subnombre, "") = "", cat_prueba.catPrueba_Nombre, CONCAT(cat_prueba.catPrueba_Nombre, " (", TABLA1.agente_subnombre, ")")) AS catPrueba_Nombre,
                                    cat_prueba.catPrueba_Nombre AS agente_nombre,
                                    TABLA1.agente_subnombre AS agente_subnombre,
                                    TABLA1.totalregistros,
                                    TABLA1.totalpuntos,
                                    (
                                        IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = '.$recsensorial_id.' LIMIT 1) = 1, -- Si el cliente es Pemex
                                            IF(TABLA1.catprueba_id != 17,
                                                IF(TABLA1.catprueba_id != 16,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                        WHEN TABLA1.totalpuntos >= 151 THEN "Extra grande"
                                                        WHEN TABLA1.totalpuntos >= 81 THEN "Grande"
                                                        WHEN TABLA1.totalpuntos >= 41 THEN "Mediana"
                                                        WHEN TABLA1.totalpuntos >= 21 THEN "Chica"
                                                        ELSE "Extra chica"
                                                    END
                                                    ,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                        WHEN TABLA1.totalpuntos >= 50 THEN "Extra grande"
                                                        WHEN TABLA1.totalpuntos >= 31 THEN "Grande"
                                                        WHEN TABLA1.totalpuntos >= 11 THEN "Mediana"
                                                        WHEN TABLA1.totalpuntos >= 5 THEN "Chica"
                                                        WHEN TABLA1.totalpuntos >= 2 THEN "Extra chica"
                                                        ELSE "N/A"
                                                    END
                                                )
                                                ,
                                                    "N/A"
                                            )
                                            ,
                                            IF(TABLA1.catprueba_id != 17,
                                                IF(TABLA1.catprueba_id != 16,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                        -- WHEN TABLA1.totalpuntos >= 151 THEN "Extra grande"
                                                        WHEN TABLA1.totalpuntos >= 81 THEN "Grande"
                                                        WHEN TABLA1.totalpuntos >= 41 THEN "Mediana"
                                                        WHEN TABLA1.totalpuntos >= 21 THEN "Chica"
                                                        ELSE "Extra chica"
                                                    END
                                                    ,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                        WHEN TABLA1.totalpuntos >= 50 THEN "Extra grande"
                                                        WHEN TABLA1.totalpuntos >= 31 THEN "Grande"
                                                        WHEN TABLA1.totalpuntos >= 11 THEN "Mediana"
                                                        WHEN TABLA1.totalpuntos >= 5 THEN "Chica"
                                                        WHEN TABLA1.totalpuntos >= 2 THEN "Extra chica"
                                                        ELSE "N/A"
                                                    END
                                                )
                                                ,
                                                    "N/A"
                                            )
                                        )
                                    ) AS tipoinstalacion,
                                    (
                                        IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = '.$recsensorial_id.' LIMIT 1) = 1, -- Si el cliente es Pemex
                                            IF(TABLA1.catprueba_id != 17,
                                                IF(TABLA1.catprueba_id != 16,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "0"
                                                        WHEN TABLA1.totalpuntos >= 151 THEN "XG"
                                                        WHEN TABLA1.totalpuntos >= 81 THEN "G"
                                                        WHEN TABLA1.totalpuntos >= 41 THEN "M"
                                                        WHEN TABLA1.totalpuntos >= 21 THEN "CH"
                                                        ELSE "XC"
                                                    END
                                                    ,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "0"
                                                        WHEN TABLA1.totalpuntos >= 50 THEN "XG"
                                                        WHEN TABLA1.totalpuntos >= 31 THEN "G"
                                                        WHEN TABLA1.totalpuntos >= 11 THEN "M"
                                                        WHEN TABLA1.totalpuntos >= 5 THEN "CH"
                                                        WHEN TABLA1.totalpuntos >= 2 THEN "XC"
                                                        ELSE "N/A"
                                                    END
                                                )
                                                ,
                                                    "N/A"
                                            )
                                            ,
                                            IF(TABLA1.catprueba_id != 17,
                                                IF(TABLA1.catprueba_id != 16,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "0"
                                                        -- WHEN TABLA1.totalpuntos >= 151 THEN "XG"
                                                        WHEN TABLA1.totalpuntos >= 81 THEN "G"
                                                        WHEN TABLA1.totalpuntos >= 41 THEN "M"
                                                        WHEN TABLA1.totalpuntos >= 21 THEN "CH"
                                                        ELSE "XC"
                                                    END
                                                    ,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "0"
                                                        WHEN TABLA1.totalpuntos >= 50 THEN "XG"
                                                        WHEN TABLA1.totalpuntos >= 31 THEN "G"
                                                        WHEN TABLA1.totalpuntos >= 11 THEN "M"
                                                        WHEN TABLA1.totalpuntos >= 5 THEN "CH"
                                                        WHEN TABLA1.totalpuntos >= 2 THEN "XC"
                                                        ELSE "N/A"
                                                    END
                                                )
                                                ,
                                                    "N/A"
                                            )
                                        )
                                    ) AS tipoinstalacion_abreviacion,
                                    (
                                        IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = '.$recsensorial_id.' LIMIT 1) = 1, -- Si el cliente es Pemex
                                            IF(TABLA1.catprueba_id != 17,
                                                IF(TABLA1.catprueba_id != 16,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                        WHEN TABLA1.totalpuntos >= 151 THEN "#DF0101"
                                                        WHEN TABLA1.totalpuntos >= 81 THEN "#FF8000"
                                                        WHEN TABLA1.totalpuntos >= 41 THEN "#FFD700"
                                                        WHEN TABLA1.totalpuntos >= 21 THEN "#5FB404"
                                                        ELSE "#0080FF"
                                                    END
                                                    ,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                        WHEN TABLA1.totalpuntos >= 50 THEN "#DF0101"
                                                        WHEN TABLA1.totalpuntos >= 31 THEN "#FF8000"
                                                        WHEN TABLA1.totalpuntos >= 11 THEN "#FFD700"
                                                        WHEN TABLA1.totalpuntos >= 5 THEN "#5FB404"
                                                        WHEN TABLA1.totalpuntos >= 2 THEN "#0080FF"
                                                        ELSE "#999999"
                                                    END
                                                )
                                                ,
                                                "#999999"
                                            )
                                            ,
                                            IF(TABLA1.catprueba_id != 17,
                                                IF(TABLA1.catprueba_id != 16,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                        -- WHEN TABLA1.totalpuntos >= 151 THEN "#DF0101"
                                                        WHEN TABLA1.totalpuntos >= 81 THEN "#FF8000"
                                                        WHEN TABLA1.totalpuntos >= 41 THEN "#FFD700"
                                                        WHEN TABLA1.totalpuntos >= 21 THEN "#5FB404"
                                                        ELSE "#0080FF"
                                                    END
                                                    ,
                                                    CASE
                                                        WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                        WHEN TABLA1.totalpuntos >= 50 THEN "#DF0101"
                                                        WHEN TABLA1.totalpuntos >= 31 THEN "#FF8000"
                                                        WHEN TABLA1.totalpuntos >= 11 THEN "#FFD700"
                                                        WHEN TABLA1.totalpuntos >= 5 THEN "#5FB404"
                                                        WHEN TABLA1.totalpuntos >= 2 THEN "#0080FF"
                                                        ELSE "#999999"
                                                    END
                                                )
                                                ,
                                                "#999999"
                                            )
                                        )
                                    ) AS tipoinstalacion_color
                                FROM
                                    (
                                        (
                                            SELECT
                                                TABLARUIDO.catprueba_id,
                                                NULL AS agente_subnombre,
                                                SUM(TABLARUIDO.totalregistros) AS totalregistros,
                                                SUM(TABLARUIDO.totalpuntos) AS totalpuntos
                                            FROM
                                                (
                                                    (
                                                        -- SELECT
                                                        --     1 AS catprueba_id,
                                                        --     NULL AS agente_subnombre,
                                                        --     COUNT(parametroruidosonometria.recsensorial_id) AS totalregistros,
                                                        --     SUM(parametroruidosonometria.parametroruidosonometria_puntos) AS totalpuntos 
                                                        -- FROM
                                                        --     parametroruidosonometria
                                                        -- WHERE
                                                        --     parametroruidosonometria.recsensorial_id = 182
                                                        -- GROUP BY
                                                        --     parametroruidosonometria.recsensorial_id,
                                                        --     parametroruidosonometria.parametroruidosonometria_puntos

                                                        SELECT
                                                            1 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT(tablaresumen.recsensorialarea_id) AS totalregistros,
                                                            SUM(tablaresumen.puntos) AS totalpuntos
                                                        FROM
                                                            (
                                                                SELECT
                                                                    tabla.recsensorial_id,
                                                                    tabla.recsensorialarea_id,
                                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                                    IFNULL((
                                                                        SELECT
                                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<br/>● "))
                                                                        FROM
                                                                            parametroruidosonometriacategorias
                                                                            LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                                                        WHERE
                                                                            parametroruidosonometriacategorias.recsensorialarea_id = tabla.recsensorialarea_id
                                                                    ), "-") AS categorias,
                                                                    tabla.parametroruidosonometria_puntos AS puntos,
                                                                    MIN(tabla.medicion) AS min,
                                                                    MAX(tabla.medicion) AS max,
                                                                    (MAX(tabla.medicion) - MIN(tabla.medicion)) AS diferencia,
                                                                    (
                                                                        CASE
                                                                            WHEN (COUNT(tabla.medicion) = 0) THEN 1
                                                                            WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    ) AS aplicaevaluacion,
                                                                    IFNULL((
                                                                        REPLACE(GROUP_CONCAT(tabla.medicion_texto ORDER BY tabla.medicion_texto ASC), ",", "<br/>")
                                                                    ), "NP") AS mediciones,
                                                                    (
                                                                        CASE
                                                                            WHEN (COUNT(tabla.medicion) = 0) THEN "Se evalua"
                                                                            WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 
                                                                                CASE
                                                                                    WHEN ((MAX(tabla.medicion) - MIN(tabla.medicion)) > 5) THEN (CONCAT("Se evalua<br/>Ruido inestable<br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                                    ELSE (CONCAT("Se evalua<br/>Ruido Estable<br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                                END
                                                                            ELSE "No se evalua<br/>≤80 dB"
                                                                        END
                                                                    ) AS resultado
                                                                FROM
                                                                    (
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmax) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, CONCAT("Max: ", parametroruidosonometria.parametroruidosonometria_medmax, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmin) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, CONCAT("Min: ", parametroruidosonometria.parametroruidosonometria_medmin, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,                       
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med1) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, CONCAT("Med01: ", parametroruidosonometria.parametroruidosonometria_med1, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,               
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med2) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, CONCAT("Med02: ", parametroruidosonometria.parametroruidosonometria_med2, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med3) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, CONCAT("Med03: ", parametroruidosonometria.parametroruidosonometria_med3, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med4) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, CONCAT("Med04: ", parametroruidosonometria.parametroruidosonometria_med4, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med5) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, CONCAT("Med05: ", parametroruidosonometria.parametroruidosonometria_med5, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med6) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, CONCAT("Med06: ", parametroruidosonometria.parametroruidosonometria_med6, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med7) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, CONCAT("Med07: ", parametroruidosonometria.parametroruidosonometria_med7, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med8) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, CONCAT("Med08: ", parametroruidosonometria.parametroruidosonometria_med8, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med9) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, CONCAT("Med09: ", parametroruidosonometria.parametroruidosonometria_med9, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                        UNION ALL
                                                                        (
                                                                            SELECT
                                                                                parametroruidosonometria.recsensorial_id, 
                                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med10) AS medicion,
                                                                                IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, CONCAT("Med10: ", parametroruidosonometria.parametroruidosonometria_med10, " dB")) AS medicion_texto
                                                                            FROM
                                                                                parametroruidosonometria
                                                                        )
                                                                    ) AS tabla
                                                                    LEFT JOIN recsensorialarea ON tabla.recsensorialarea_id = recsensorialarea.id
                                                                WHERE
                                                                    tabla.recsensorial_id = '.$recsensorial_id.' 
                                                                GROUP BY
                                                                    tabla.recsensorial_id,
                                                                    tabla.recsensorialarea_id,
                                                                    parametroruidosonometria_puntos
                                                                ORDER BY
                                                                    tabla.recsensorialarea_id ASC
                                                            ) AS tablaresumen
                                                        WHERE
                                                            tablaresumen.aplicaevaluacion = 1
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            1 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT(parametroruidodosimetria.recsensorial_id) AS totalregistros,
                                                            SUM(parametroruidodosimetria.parametroruidodosimetria_dosis) AS totalpuntos 
                                                        FROM
                                                            parametroruidodosimetria
                                                        WHERE
                                                            parametroruidodosimetria.recsensorial_id = '.$recsensorial_id.' 
                                                        GROUP BY
                                                            parametroruidodosimetria.recsensorial_id,
                                                            parametroruidodosimetria.parametroruidodosimetria_dosis
                                                    )
                                                ) AS TABLARUIDO
                                            GROUP BY
                                                TABLARUIDO.catprueba_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                2 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT(parametrovibracion.recsensorial_id) AS totalregistros,
                                                SUM(parametrovibracion.parametrovibracion_puntovce + parametrovibracion.parametrovibracion_puntoves) AS totalpuntos  
                                            FROM
                                                parametrovibracion
                                            WHERE
                                                parametrovibracion.recsensorial_id = '.$recsensorial_id.'
                                            GROUP BY
                                                parametrovibracion.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                3 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametrotemperatura.recsensorial_id ) AS totalregistros,
                                                SUM( parametrotemperatura.parametrotemperatura_puntote + parametrotemperatura.parametrotemperatura_puntota ) AS totalpuntos 
                                            FROM
                                                parametrotemperatura 
                                            WHERE
                                                parametrotemperatura.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametrotemperatura.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                4 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametroiluminacion.recsensorial_id ) AS totalregistros,
                                                SUM( parametroiluminacion.parametroiluminacion_puntos ) AS totalpuntos 
                                            FROM
                                                parametroiluminacion 
                                            WHERE
                                                parametroiluminacion.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametroiluminacion.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                5 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametroradiacionionizante.recsensorial_id ) AS totalregistros,
                                                SUM( parametroradiacionionizante.parametroradiacionionizante_puntos ) AS totalpuntos 
                                            FROM
                                                parametroradiacionionizante 
                                            WHERE
                                                parametroradiacionionizante.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametroradiacionionizante.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                6 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametroradiacionnoionizante.recsensorial_id ) AS totalregistros,
                                                SUM( parametroradiacionnoionizante.parametroradiacionnoionizante_puntos ) AS totalpuntos 
                                            FROM
                                                parametroradiacionnoionizante 
                                            WHERE
                                                parametroradiacionnoionizante.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametroradiacionnoionizante.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                7 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametroprecionesambientales.recsensorial_id ) AS totalregistros,
                                                SUM( parametroprecionesambientales.parametroprecionesambientales_puntos ) AS totalpuntos 
                                            FROM
                                                parametroprecionesambientales 
                                            WHERE
                                                parametroprecionesambientales.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametroprecionesambientales.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                8 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametrocalidadaire.recsensorial_id ) AS totalregistros,
                                                SUM( parametrocalidadaire.parametrocalidadaire_puntos ) AS totalpuntos 
                                            FROM
                                                parametrocalidadaire 
                                            WHERE
                                                parametrocalidadaire.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametrocalidadaire.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                9 AS catprueba_id,
                                                "Fisicoquímico" AS agente_subnombre,
                                                Count( parametroagua.recsensorial_id ) AS totalregistros,
                                                Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                            FROM
                                                parametroagua
                                                INNER JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                            WHERE
                                                parametroagua.recsensorial_id = '.$recsensorial_id.' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Fisicoquímico"
                                            GROUP BY
                                                parametroagua.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                9 AS catprueba_id,
                                                "Microbiológico" AS agente_subnombre,
                                                Count( parametroagua.recsensorial_id ) AS totalregistros,
                                                Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                            FROM
                                                parametroagua
                                                LEFT JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                            WHERE
                                                parametroagua.recsensorial_id = '.$recsensorial_id.' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Microbiológico"
                                            GROUP BY
                                                parametroagua.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                10 AS catprueba_id,
                                                "Fisicoquímico" AS agente_subnombre,
                                                Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                                Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                            FROM
                                                parametrohielo
                                                INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                            WHERE
                                                parametrohielo.recsensorial_id = '.$recsensorial_id.' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Fisicoquímico"
                                            GROUP BY
                                                parametrohielo.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                10 AS catprueba_id,
                                                "Microbiológico" AS agente_subnombre,
                                                Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                                Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                            FROM
                                                parametrohielo
                                                INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                            WHERE
                                                parametrohielo.recsensorial_id = '.$recsensorial_id.' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Microbiológico"
                                            GROUP BY
                                                parametrohielo.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                11 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametroalimento.recsensorial_id ) AS totalregistros,
                                                SUM( parametroalimento.parametroalimento_puntos ) AS totalpuntos 
                                            FROM
                                                parametroalimento 
                                            WHERE
                                                parametroalimento.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametroalimento.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                12 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametrosuperficie.recsensorial_id ) AS totalregistros,
                                                SUM( parametrosuperficie.parametrosuperficie_puntos ) AS totalpuntos 
                                            FROM
                                                parametrosuperficie 
                                            WHERE
                                                parametrosuperficie.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametrosuperficie.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                13 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                TABLAERGONOMIA.recsensorial_id,
                                                SUM(TABLAERGONOMIA.totalpuntos) AS totalpuntos
                                            FROM
                                                (
                                                    SELECT
                                                        parametroergonomia.recsensorial_id,
                                                        parametroergonomia.id,
                                                        IF((parametroergonomia.parametroergonomia_movimientorepetitivo + parametroergonomia.parametroergonomia_posturamantenida + parametroergonomia.parametroergonomia_posturaforzada + parametroergonomia.parametroergonomia_cargamanual +parametroergonomia.parametroergonomia_fuerza) > 1, 1, 0) AS totalpuntos
                                                    FROM
                                                        parametroergonomia
                                                    WHERE
                                                        parametroergonomia.recsensorial_id = '.$recsensorial_id.'
                                                    GROUP BY
                                                        parametroergonomia.recsensorial_id,
                                                        parametroergonomia.id,
                                                        parametroergonomia.parametroergonomia_movimientorepetitivo,
                                                        parametroergonomia.parametroergonomia_posturamantenida,
                                                        parametroergonomia.parametroergonomia_posturaforzada,
                                                        parametroergonomia.parametroergonomia_cargamanual,
                                                        parametroergonomia.parametroergonomia_fuerza
                                                ) AS TABLAERGONOMIA
                                            WHERE
                                                TABLAERGONOMIA.totalpuntos > 0
                                            GROUP BY
                                                TABLAERGONOMIA.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                14 AS catprueba_id,
                                                NULL AS agente_subnombre,
                                                COUNT( parametropsicosocial.recsensorial_id ) AS totalregistros,
                                                SUM( parametropsicosocial.parametropsicosocial_nopersonas ) AS totalpuntos 
                                            FROM
                                                parametropsicosocial 
                                            WHERE
                                                parametropsicosocial.recsensorial_id = '.$recsensorial_id.' 
                                            GROUP BY
                                                parametropsicosocial.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                TABLASERVICIOPERSONAL.catprueba_id,
                                                TABLASERVICIOPERSONAL.agente_subnombre,
                                                TABLASERVICIOPERSONAL.totalregistros,
                                                TABLASERVICIOPERSONAL.totalpuntos
                                            FROM
                                                (
                                                    SELECT
                                                        16 AS catprueba_id,
                                                        NULL AS agente_subnombre,
                                                        COUNT( parametroserviciopersonal.recsensorial_id ) AS totalregistros,
                                                        SUM( parametroserviciopersonal.parametroserviciopersonal_puntos ) AS totalpuntos 
                                                    FROM
                                                        parametroserviciopersonal 
                                                    WHERE
                                                        parametroserviciopersonal.recsensorial_id = '.$recsensorial_id.' 
                                                    GROUP BY
                                                        parametroserviciopersonal.recsensorial_id
                                                ) AS TABLASERVICIOPERSONAL
                                            WHERE
                                                TABLASERVICIOPERSONAL.totalpuntos > 1
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                  17 AS catprueba_id,
                                                  "Tipo 1" AS agente_subnombre,
                                                  COUNT(parametromapariesgo.parametromapariesgo_tipo1) AS totalregistros,
                                                  SUM(parametromapariesgo.parametromapariesgo_tipo1) AS totalpuntos
                                            FROM
                                                  parametromapariesgo
                                            WHERE
                                                  parametromapariesgo.recsensorial_id = '.$recsensorial_id.'
                                            GROUP BY
                                                  parametromapariesgo.recsensorial_id
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                17 AS catprueba_id,
                                                "Tipo 2" AS agente_subnombre,
                                                COUNT(parametromapariesgo.parametromapariesgo_tipo2) AS totalregistros,
                                                SUM(parametromapariesgo.parametromapariesgo_tipo2) AS totalpuntos
                                            FROM
                                                parametromapariesgo
                                            WHERE
                                                parametromapariesgo.recsensorial_id = '.$recsensorial_id.'
                                            GROUP BY
                                                parametromapariesgo.recsensorial_id
                                        )
                                    ) TABLA1
                                    LEFT JOIN recsensorialpruebas ON TABLA1.catprueba_id = recsensorialpruebas.catprueba_id
                                    LEFT JOIN cat_prueba ON recsensorialpruebas.catprueba_id = cat_prueba.id
                                WHERE
                                    recsensorialpruebas.recsensorial_id = '.$recsensorial_id.' 
                                    AND recsensorialpruebas.catprueba_id != 15
                                    AND TABLA1.totalpuntos > 0
                                GROUP BY
                                    recsensorialpruebas.recsensorial_id,
                                    cat_prueba.catPrueba_Tipo,
                                    TABLA1.catprueba_id,
                                    cat_prueba.catPrueba_Nombre,
                                    TABLA1.agente_subnombre,
                                    TABLA1.totalregistros,
                                    TABLA1.totalpuntos
                                ORDER BY
                                    TABLA1.totalpuntos DESC,
                                    cat_prueba.catPrueba_Nombre ASC');


            // dibujar filas tabla
            $tabla = '';
            foreach ($sql  as $key => $value) 
            {
                $tabla .= '<tr>';
                    // $tabla .= '<td><span class="round" style="background-color: '.$value->tipoinstalacion_color.'"><i style="font-style: normal;">'.$value->tipoinstalacion_abreviacion.'</i></span></td>';
                    $tabla .= '<td><h6>'.$value->catPrueba_Nombre.'</h6><small class="text-muted">Registros: '.$value->totalregistros.'</small></td>';
                    // $tabla .= '<td><b>'.$value->totalpuntos.'</b></td>';
                    $tabla .= '<td><span class="round" style="background-color: '.$value->tipoinstalacion_color.';"><i style="font-weight: normal; font-size: 15px!important;">'.$value->totalpuntos.'</i></span></td>';
                $tabla .= '</tr>';
            }

            // respuesta
            $dato['tabla'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicosresumentabla($recsensorial_id)
    {
        try
        {
            $tot_registros = 0;

            $sql = DB::select('CALL sp_obtener_puntos_finales_b(?)', [$recsensorial_id]);

            // dibujar filas tabla
            $tabla = '';
            foreach ($sql as $key => $value) 
            {
                $tot_registros += 1;

                $tabla .= '<tr>';
                $tabla .= '<td><h6>' . $value->PRODUCTO_COMPONENTE . '</h6><small class="text-muted">Registros: ' . $value->TOTAL_REGISTROS . '</small></td>';
                $tabla .= '<td><span class="round" style="background-color: #5FB404;"><i style="font-weight: normal; font-size: 15px!important;">' . $value->TOTAL_MUESTREO . '</i></span></td>';
                   
                $tabla .= '</tr>';
            }


            // si no hay registros
            if ($tot_registros == 0) {
                $tabla .= '<tr><td colspan="4">Los puntos de muestreo aún no han sido confirmados</td></tr>';
            }

            // respuesta
            $dato['tabla'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }

}
