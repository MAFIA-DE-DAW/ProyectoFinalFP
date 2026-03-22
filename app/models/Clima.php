<?php

// Modelo Clima
// Se encarga de consultar la API del tiempo y devolver la clase visual
class Clima
{
    // Método para obtener la clase CSS del clima según la API
    public static function obtenerClaseClima()
    {
        $apiKey = "2b38874df3e4f0aab602b288b36e2fe2";
        $ciudad = "Madrid";

        $url = "https://api.openweathermap.org/data/2.5/weather?q=$ciudad&appid=$apiKey&units=metric&lang=es";

        $respuesta = @file_get_contents($url);

        $clase_clima = "clima-normal";

        if ($respuesta !== false) {
            $datos = json_decode($respuesta, true);
            $clima = $datos["weather"][0]["main"];
            $lluvia = isset($datos["rain"]["1h"]) ? $datos["rain"]["1h"] : 0;

            if ($lluvia > 0 || in_array($clima, ["Rain", "Drizzle", "Mist", "Haze"])) {
                $clase_clima = "clima-lluvia";
            } elseif ($clima === "Clear") {
                $clase_clima = "clima-sol";
            } elseif ($clima === "Clouds") {
                $clase_clima = "clima-nubes";
            } elseif ($clima === "Thunderstorm") {
                $clase_clima = "clima-tormenta";
            }
        }

        return $clase_clima;
    }
}