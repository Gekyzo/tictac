<?php

namespace App;

class Board
{
    public $db;

    public $state;

    public function __construct()
    {
        $this->db = new Database;
        $this->state = $this->setState();
    }

    public function setState()
    {
        $moves = $this->db->getMoves();

        return $moves;
    }

    public function getState()
    {
        return $this->state;
    }

    public function draw()
    {
        $this->drawTable();
        $this->displayResetBtn();
    }

    private function drawTable()
    {
        $player = $this->setPlayer();

        printf('<form method="post" action="%s" id="board">', $_SERVER['PHP_SELF']);

        printf('<input type="hidden" name="player" value="%s">', $player);

        for ($i = 1; $i < 10; $i++) {
            $this->drawSquare($i);
            if ($i % 3 === 0) {
                echo '<br>';
            }
        }

        printf('</form>');
    }

    private function displayResetBtn()
    {
        if (!empty($this->state)) {
            printf('<form method="get" action="%s" id="btn_reset">', $_SERVER['PHP_SELF']);
            printf('<input type="hidden" name="action" value="reset">');
            printf('<button>Nueva partida</button>');
            printf('</form>');
        }
    }

    private function setPlayer(): int
    {
        $lastPlayer = $this->db->getLastPlayer();

        if ($lastPlayer === false) {
            return 0;
        } else {
            return (int) $lastPlayer === 0 ? 1 : 0;
        }
    }

    private function drawSquare($i)
    {
        $empty = true;

        foreach ($this->getState() as $key => $value) {
            $playerChar = (int) $value['player_id'] === 0 ? 'x' : 'o';

            if ((int)$value['square_id'] === $i) {
                $empty = false;
                printf('<button name="move" disabled class="player_%s">%s</button>', $value['player_id'], $playerChar);
            }
        }

        if ($empty == true) {
            printf('<button name="move" value="%s">&nbsp;</button>', $i,);
        }
    }
}
