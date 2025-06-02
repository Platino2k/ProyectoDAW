<?php
// Parametros de edificios y tropas
$building =[
    // Campo
    // Produccion por minuto
    "food_1" => [1],
    "food_2" => [2],
    "food_3" => [3],
    "food_4" => [4],

    "wood_1" => [1],
    "wood_2" => [2],
    "wood_3" => [3],
    "wood_4" => [4],

    "stone_1" => [1],
    "stone_2" => [2],
    "stone_3" => [3],
    "stone_4" => [4],

    "iron_1" => [1],
    "iron_2" => [2],
    "iron_3" => [3],
    "iron_4" => [4],

    // Ciudad

    //Solo especifica nivel
    "townhall_1" => [1],
    "townhall_2" => [2],

    // Capacidad Máxima
    "storehouse_1" => [500,500,500,500],
    "storehouse_2" => [1000,1000,1000,1000],
    "storehouse_3" => [1500,1500,1500,1500],

    // Tropas por nivel
    "barracks_1" => ["SWORDMAN"],
    "barracks_2" => ["SWORDMAN","PIKEMAN"],
    "barracks_3" => ["SWORDMAN","PIKEMAN","ARCHER"],

    "stable_1" => ["L_CAVALRY"],
    "stable_2" => ["L_CAVALRY","H_CAVALRY"],
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
    // Comida - Madera - Piedra - Hierro - Reclutar (en Minutos)
    //Cuartel
    "SWORDMAN" => [25,10,0,25,1],
    "PIKEMAN" => [40,20,0,40,2],
    "ARCHER" =>  [40,40,0,30,2],

    //Establo
    "L_CAVALRY" => [70,30,0,50,3],
    "H_CAVALRY" => [100,50,0,70,5],
];

// Requisitos de edificios
$buildingREQ = [

    // El requisito para X nivel de edificio es X nivel de ayuntamiento
    // Campo
    "food_2" => [1],
    "food_3" => [2],
    "food_4" => [2],

    "wood_2" => [1],
    "wood_3" => [2],
    "wood_4" => [2],

    "stone_2" => [1],
    "stone_3" => [2],
    "stone_4" => [2],

    "iron_2" => [1],
    "iron_3" => [2],
    "iron_4" => [2],

    // Ciudad

    "townhall_2" => [1],

    "storehouse_2" => [1],
    "storehouse_3" => [2],

    // Edificios militares
    "barracks_1" => [1],
    "barracks_2" => [1],
    "barracks_3" => [2],

    "stable_1" => [1],
    "stable_2" => [2],
    
];

// Coste Edificios
$buildingCOST = [
    // Campo
    // Comida - Madera - Piedra - Hierro - Tiempo (en Minutos)
    "food_2" => [100,100,0,0,2],
    "food_3" => [120,180,0,0,5],
    "food_4" => [200,300,0,0,10],

    "wood_2" => [20,100,50,50,2],
    "wood_3" => [30,130,80,80,5],
    "wood_4" => [60,170,130,130,10],

    "stone_2" => [20,100,20,100,2],
    "stone_3" => [30,130,30,120,5],
    "stone_4" => [40,200,50,150,10],

    "iron_2" => [50,50,0,100,2],
    "iron_3" => [80,80,0,150,5],
    "iron_4" => [120,120,0,200,10],

    // Ciudad
    "townhall_2" => [350,350,300,250,5],

    "storehouse_2" => [200,200,200,0,5],
    "storehouse_3" => [300,300,300,0,10],

    "barracks_1" => [0,200,200,100,2],
    "barracks_2" => [0,250,250,150,5],
    "barracks_3" => [0,300,300,200,10],

    "stable_1" => [0,200,200,100,2],
    "stable_2" => [0,250,250,150,5],
];

?>