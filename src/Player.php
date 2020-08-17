<?php

namespace App;

class Player
{
    public $boardState;

    public function checkWinner($boardState)
    {
        $this->boardState = $boardState;

        $firstPlayerStatus = $this->checkStatusForPlayer(0);
        $secondPlayerStatus = $this->checkStatusForPlayer(1);

        if ($firstPlayerStatus) {
            return 0;
        }

        if ($secondPlayerStatus) {
            return 1;
        }

        return false;
    }

    private function checkStatusForPlayer(int $playerId)
    {
        $playerMoves = array_filter($this->boardState, function ($val) use ($playerId) {
            if ((int) $val['player_id'] === $playerId) {
                return $val;
            }
        });

        if (count($playerMoves) < 3) {
            return false;
        }

        $playerMovesSquares = [];
        foreach ($playerMoves as $value) {
            array_push($playerMovesSquares, (int) $value['square_id']);
        }
        asort($playerMovesSquares);

        $playerMovesSquares = array_values($playerMovesSquares);

        $isWinner = $this->checkMoveStatus($playerMovesSquares);

        return $isWinner;
    }

    private function checkMoveStatus(array $squares)
    {
        $colWinner = $this->isWinnerCol($squares);
        $rowWinner = $this->isWinnerRow($squares);
        $diagWinner = $this->isWinnerDiag($squares);

        return ($colWinner || $rowWinner || $diagWinner);
    }

    private function isWinnerCol(array $squares): bool
    {
        if ($squares[0] + 3 === $squares[1] && $squares[1] + 3 === $squares[2]) {
            return true;
        } else {
            return false;
        }
    }

    private function isWinnerRow(array $squares): bool
    {
        if ($squares[0] + 1 === $squares[1] && $squares[1] + 1 === $squares[2]) {
            $firstCond = true;
        } else {
            $firstCond = false;
        }

        if (in_array($squares[0], [1, 4, 7])) {
            $secondCond = true;
        } else {
            $secondCond = false;
        }

        return $firstCond && $secondCond;
    }

    private function isWinnerDiag(array $squares): bool
    {
        $winner = false;
        $winnerDiagonals = [
            [1, 5, 9],
            [3, 5, 7]
        ];

        foreach ($winnerDiagonals as $winnerDiagonal) {
            if (count(array_intersect($squares, $winnerDiagonal)) === 3) {
                $winner = true;
            }
        }

        return $winner;
    }
}
