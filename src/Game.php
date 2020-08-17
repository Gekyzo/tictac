<?php

namespace App;

class Game
{
    public $db;
    public $board;
    public $player;

    public function __construct()
    {
        $this->db = new Database;
        $this->board = new Board;
        $this->player = new Player;

        $this->checkState();
        $this->board->draw();
        $this->checkNewMoves();
    }

    private function checkNewMoves()
    {
        $move = $this->getMove();

        if (!is_null($move)) {
            $this->db->saveMove($move);
            $this->refresh();
        }
    }

    private function getMove()
    {
        if ($_POST) {
            return $_POST;
        } else {
            return null;
        }
    }

    private function refresh()
    {
        header("Location: http://" . $_SERVER['HTTP_HOST']);
    }

    private function checkState()
    {
        $this->checkIfReset();

        $winner = $this->player->checkWinner($this->board->getState());

        if ($winner !== false) {
            $this->displayWinner($winner);
        }

        if (count($this->board->getState()) === 9) {
            $this->displayWinner('draw');
        }
    }

    private function checkIfReset()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'reset') {
            $this->db->resetGame();
            $this->refresh();
        }
    }

    private function displayWinner($winnerId)
    {
        $winnerChar = $winnerId === 0 ? 'x' : 'o';

        if (is_numeric($winnerId)) {
            printf('<p class="game_result" id="game_result_%s">Gana el jugador: <span class="player_%s">%s</span></p>', $winnerId, $winnerId, $winnerChar);
        }

        if ($winnerId === 'draw') {
            printf('<p class="game_result" id="game_result_draw">La partida termina en empate</p>');
        }
    }
}
