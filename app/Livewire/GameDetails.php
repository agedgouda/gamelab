<?php

namespace App\Livewire;
use App\Models\Game;

use Livewire\Component;

class GameDetails extends Component
{
    public $bggId;
    public $bggGameData;
    public $game;

    public function mount($bggId) 
    {
        $this->bggId = $bggId;
        $this->game = Game::where('bgg_id',$bggId)->first();
        $this->fetchGameData();
    }

    public function fetchGameData()
    {
        // Fetch XML data from the Board Game Geek API
        $url = "https://boardgamegeek.com/xmlapi/game/{$this->bggId}";
        $xmlData = file_get_contents($url);

        // Parse the XML response
        if ($xmlData !== false) {
            $xml = new \SimpleXMLElement($xmlData);
            $boardgameData = json_decode(json_encode($xml), true)['boardgame'] ?? null;
            $this->bggGameData = $boardgameData;

            // If board game data exists, process the suggested number of players
            
            if(isset($this->bggGameData['boardgamesubdomain'])) {
                if (is_array($this->bggGameData['boardgamesubdomain'])){
                    $this->bggGameData['type'] = implode(", ",$this->bggGameData['boardgamesubdomain']);
                }
                else {
                    $this->bggGameData['type'] = $this->bggGameData['boardgamesubdomain'];
                }
                $this->bggGameData['type'] = str_replace(" Games","",$this->bggGameData['type']);
            }
        
            
            if(isset($this->bggGameData['boardgameexpansion'])) {
                if (is_array($this->bggGameData['boardgameexpansion'])){
                    $this->bggGameData['expansions'] = implode("</br>",$this->bggGameData['boardgameexpansion']);
                }
                else {
                    $this->bggGameData['expansions'] = $this->bggGameData['boardgameexpansion'];
                }
            } else {
                $this->bggGameData['expansions'] = '';
            }




            if (isset($this->bggGameData['poll'])) {
            foreach ($this->bggGameData['poll'] as $poll) {
                if (isset($poll['@attributes']['name']) && $poll['@attributes']['name'] == 'suggested_numplayers') {
                    // Process the suggested number of players data
                    $suggestedPlayersData = $poll['results'];

                    // Initialize variables to track the best player count and votes
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

                    // Add the suggested number of players to the game data
                    $this->bggGameData['suggested_players'] = $bestPlayers;
                }
            }
        }



        } else {
            $this->bggGameData = null; // Handle error in fetching data
        }
    }

    public function render()
    {
        return view('livewire.game.game-details');
    }
}
