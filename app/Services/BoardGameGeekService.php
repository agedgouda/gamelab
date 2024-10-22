<?php

namespace App\Services;

use SimpleXMLElement;

class BoardGameGeekService
{
    public function fetchGameData($bggId)
    {
        // Fetch XML data from the Board Game Geek API
        $url = "https://boardgamegeek.com/xmlapi/game/{$bggId}";
        $xmlData = file_get_contents($url);

        // Parse the XML response
        if ($xmlData !== false) {
            $xml = new SimpleXMLElement($xmlData);
            $boardgameData = json_decode(json_encode($xml), true)['boardgame'] ?? null;

            // If board game data exists, process it
            if ($boardgameData) {
                // Process the game type
                if (isset($boardgameData['boardgamesubdomain'])) {
                    $boardgameData['type'] = is_array($boardgameData['boardgamesubdomain'])
                        ? implode(", ", $boardgameData['boardgamesubdomain'])
                        : $boardgameData['boardgamesubdomain'];

                    $boardgameData['type'] = str_replace(" Games", "", $boardgameData['type']);
                }

                // Process the expansions
                if (isset($boardgameData['boardgameexpansion'])) {
                    $boardgameData['expansions'] = is_array($boardgameData['boardgameexpansion'])
                        ? implode("</br>", $boardgameData['boardgameexpansion'])
                        : $boardgameData['boardgameexpansion'];
                } else {
                    $boardgameData['expansions'] = '';
                }

                // Process the suggested number of players
                if (isset($boardgameData['poll'])) {
                    foreach ($boardgameData['poll'] as $poll) {
                        if (isset($poll['@attributes']['name']) && $poll['@attributes']['name'] === 'suggested_numplayers') {
                            $suggestedPlayersData = $poll['results'];
                            $bestPlayers = null;
                            $bestVotes = 0;

                            foreach ($suggestedPlayersData as $result) {
                                foreach ($result['result'] as $vote) {
                                    if ($vote["@attributes"]["value"] === "Best" && $vote["@attributes"]["numvotes"] > $bestVotes) {
                                        $bestVotes = $vote["@attributes"]["numvotes"];
                                        $bestPlayers = $result["@attributes"]["numplayers"];
                                    }
                                }
                            }

                            $boardgameData['suggested_players'] = $bestPlayers;
                        }
                    }
                }
            }

            return $boardgameData;
        }

        return null; // Handle error in fetching data
    }
}
