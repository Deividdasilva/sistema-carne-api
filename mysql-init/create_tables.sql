CREATE TABLE IF NOT EXISTS carne (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor_total FLOAT NOT NULL,
    qtd_parcelas INT NOT NULL,
    data_primeiro_vencimento DATE NOT NULL,
    periodicidade VARCHAR(10) NOT NULL,
    valor_entrada FLOAT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS parcelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carne_id INT NOT NULL,
    numero INT NOT NULL,
    data_vencimento DATE NOT NULL,
    valor FLOAT NOT NULL,
    entrada BOOLEAN DEFAULT 0,
    FOREIGN KEY (carne_id) REFERENCES carne(id)
);
