<?php 

    namespace Embryo\Database;
    
    use Embryo\Database\Query;

    class QueryBuilder
    {
        private $pdo;
        private $table;
        private $select = '';
        private $where = '';
        private $andWhere = [];
        private $orderBy = '';

        public function __construct($pdo, $table)
        {
            $this->pdo = $pdo;
            $this->table = $table;
        }

        public function select(...$field)
        {
            $this->select = (!empty($field)) ? join(', ', $field) : '*';
            return $this->execute();
        }

        public function insert(array $values)
        {
            // $db->table('test')->insert(['a' => 1]);
        }

        public function update(array $values)
        {
            // $db->table('test')->update(['a' => 1])->where(['id' => 1])->execute();
        }

        public function delete()
        {
            // $db->table('test')->delete(['a' => 1])->where(['id' => 1])->execute();
        }

        public function where($field, $operatorValue, $value = null)
        {
            $operators = ['=', '>', '>=', '<', '<='. '!='];
            
            if (in_array($operatorValue, $operators)) {
                
                $this->where = [
                    'field'    => $field,
                    'operator' => $operatorValue,
                    'value'    => $value
                ];

            } else {
                
                $this->where = [
                    'field'    => $field,
                    'operator' => '=',
                    'value'    => $operatorValue
                ];
            }
            return $this;
        }

        public function andWhere($field, $operatorValue, $value = null)
        {
            $operators = ['=', '>', '>=', '<', '<='. '!='];
            
            if (in_array($operatorValue, $operators)) {
                
                $this->andWhere[] = [
                    'field'    => $field,
                    'operator' => $operatorValue,
                    'value'    => $value
                ];

            } else {
                
                $this->andWhere[] = [
                    'field'    => $field,
                    'operator' => '=',
                    'value'    => $operatorValue
                ];
            }
            return $this;
        }

        public function orWhere()
        {

        }

        public function orderBy($field, $flag = 'ASC')
        {
            $this->orderBy = $field.' '.$flag;
            return $this;
        }

        public function groupBy()
        {

        }

        public function field()
        {

        }

        public function get()
        {

        }

        public function count()
        {

        }

        private function execute()
        {
            $query  = '';
            $values = [];

            // select
            if ($this->select) {
                $query .= 'SELECT '.$this->select. ' FROM '.$this->table;
            }

            // where
            if (!empty($this->where)) {
                
                $whereRaw = $this->where['field'].' '.$this->where['operator'].' :'.$this->where['field'];
                $query .= ' WHERE '.$whereRaw;
                $values = [
                    $this->where['field'] => $this->where['value']
                ];

            }

            // and where
            if (!empty($this->andWhere)) {
                foreach ($this->andWhere as $andWhere) {
                    $andWhereRaw = $andWhere['field'].' '.$andWhere['operator'].' :'.$andWhere['field'];
                    $query .= ' AND '.$andWhereRaw;
                    $values[$andWhere['field']] = $andWhere['value'];
                }
            }

            // order by
            if ($this->orderBy) {
                $query .= ' ORDER BY '.$this->orderBy;
            }

            return (new Query($this->pdo))
                ->query($query)
                ->values($values)
                ->executeAndFetch();
        }
    }