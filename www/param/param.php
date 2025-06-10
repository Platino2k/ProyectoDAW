<?php
// Parametros de edificios y tropas
$building =[
    // EL PRIMER CAMPO ES EL NIVEL DEL EDIFICIO
    // Campo
    // Produccion por minuto
    "food_1" => [1,1],
    "food_2" => [2,2],
    "food_3" => [3,3],
    "food_4" => [3,4],

    "wood_1" => [1,1],
    "wood_2" => [2,2],
    "wood_3" => [3,3],
    "wood_4" => [4,4],

    "stone_1" => [1,1],
    "stone_2" => [2,2],
    "stone_3" => [3,3],
    "stone_4" => [4,4],

    "iron_1" => [1,1],
    "iron_2" => [2,2],
    "iron_3" => [3,3],
    "iron_4" => [4,4],

    // Ciudad

    //Solo especifica nivel
    "townhall_1" => [1],
    "townhall_2" => [2],
    "townhall_3" => [2],

    // Capacidad Máxima
    "storehouse_1" => [1,500,500,500,500],
    "storehouse_2" => [2,1000,1000,1000,1000],
    "storehouse_3" => [3,1500,1500,1500,1500],

    // Tropas
    "barracks_1" => [1],

    "stable_1" => [1],
];

$armyPOWER = [
    //Cuartel
    "SWORDMAN" => [2],
    "PIKEMAN" => [4],
    "ARCHER" =>  [3],

    //Establo
    "L_CAVALRY" => [5],
    "H_CAVALRY" => [8],
];

$armyCOST = [
    // Madera - Comida - Piedra - Hierro
    //Cuartel
    "SWORDMAN" => [25,10,0,25],
    "PIKEMAN" => [40,20,0,40],
    "ARCHER" =>  [40,40,0,30],

    //Establo
    "L_CAVALRY" => [70,30,0,50],
    "H_CAVALRY" => [100,50,0,70],
];


// Coste Edificios
$buildingCOST = [
    // Campo
    // Comida - Madera - Piedra - Hierro
    "food_2" => [100,100,0,0],
    "food_3" => [120,180,0,0],
    "food_4" => [200,300,0,0],

    "wood_2" => [20,100,50,50],
    "wood_3" => [30,130,80,80],
    "wood_4" => [60,170,130,130],

    "stone_2" => [20,100,20,100],
    "stone_3" => [30,130,30,120],
    "stone_4" => [40,200,50,150],

    "iron_2" => [50,50,0,100],
    "iron_3" => [80,80,0,150],
    "iron_4" => [120,120,0,200],

    // Ciudad
    "townhall_2" => [350,350,300,250],
    "townhall_3" => [700,700,700,700],

    "storehouse_2" => [200,200,200,0],
    "storehouse_3" => [300,300,300,0],

    "barracks_1" => [0,200,200,100],

    "stable_1" => [0,200,200,100],
];

?>