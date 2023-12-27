<?php
    namespace App\model\server\db;
    
    use PDO, PDOException, App\model\server\db\Keys;
    
    abstract class DbConnect extends Keys{

        function connect(): PDO{
            try{
                $dns = "mysql:host={$this->host};
                port={$this->port};
                dbname={$this->db}";
    
                $this->conn = new PDO($dns, $this->user, $this->password);
    
                return $this->conn;
    
            }catch(PDOException $e){
    
                die("Morte". $e->getMessage());
    
            }
        }

        function create($tabela, $atributos, $valores){
            try {
                $this->conn = $this->connect();
        
                if($this->conn){
                    // Constrói a query de inserção
                    $atributosStr = implode(', ', $atributos);
                    $placeholders = rtrim(str_repeat('?, ', count($atributos)), ', ');
        
                    $query = "INSERT INTO $tabela ($atributosStr) VALUES ($placeholders)";
        
                    $stmt = $this->conn->prepare($query);
        
                    // Faz o bind dos valores usando bindParam
                    foreach ($valores as $key => &$value) {
                        $stmt->bindParam(($key + 1), $value); // +1 para índices iniciando em 1
                    }
        
                    $result = $stmt->execute();
        
                    if($result){
                        return "success";
                    } else {
                        return "fail";
                    }
                } else {
                    return "paunahoradeconectar";
                }
            } catch(PDOException $err){
                return "Erro" . $err->getMessage();
            }
        }
        
        function update($tabela, $atributos, $valores, $condicao){
            try {
                $this->conn = $this->connect();
        
                if($this->conn){
                    // Constrói a query de atualização
                    $setValues = [];
                    foreach($atributos as $atributo) {
                        $setValues[] = "$atributo = ?";
                    }
                    $setStr = implode(', ', $setValues);
        
                    $query = "UPDATE $tabela SET $setStr WHERE $condicao";
        
                    $stmt = $this->conn->prepare($query);
        
                    // Faz o bind dos valores usando bindParam
                    foreach ($valores as $key => &$value) {
                        $stmt->bindParam(($key + 1), $value); // +1 para índices iniciando em 1
                    }
        
                    foreach ($condicao as $key => &$value) {
                        $stmt->bindParam((count($valores) + $key + 1), $value); // +1 para índices iniciando em 1
                    }
        
                    $result = $stmt->execute();
        
                    if($result){
                        return "success";
                    } else {
                        return "fail";
                    }
                } else {
                    return "paunahoradeconectar";
                }
            } catch(PDOException $err){
                return "Erro" . $err->getMessage();
            }
        }
        
        function delete($tabela, $condicao){
            try {
                $this->conn = $this->connect();
        
                if($this->conn){
                    // Constrói a query de exclusão
                    $query = "DELETE FROM $tabela WHERE $condicao";
        
                    $stmt = $this->conn->prepare($query);
        
                    // Faz o bind dos valores usando bindParam
                    foreach ($condicao as $key => &$value) {
                        $stmt->bindParam(($key + 1), $value); // +1 para índices iniciando em 1
                    }
        
                    $result = $stmt->execute();
        
                    if($result){
                        return "success";
                    } else {
                        return "fail";
                    }
                } else {
                    return "paunahoradeconectar";
                }
            } catch(PDOException $err){
                return "Erro" . $err->getMessage();
            }
        }

    }