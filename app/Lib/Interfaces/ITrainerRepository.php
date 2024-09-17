<?php

namespace App\Lib\Interfaces;

interface ITrainerRepository
{
    public function getAllTrainers();

    public function getTrainerById(int $id);

    public function createTrainer(array $data);

    public function updateTrainer(int $id, array $data);

    public function deleteTrainer(int $id);
}
