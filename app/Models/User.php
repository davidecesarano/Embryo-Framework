<?php 

    namespace App\Models;

    use Embryo\Application\Model;

    class User extends Model
    {
        public function getAll()
        {
            return $this->pdo->table('user')->select()->all();
        }
    }