-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/06/2024 às 22:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gasto_pessoal`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `despesas`
--

CREATE TABLE `despesas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `informacao` text DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `despesas`
--

INSERT INTO `despesas` (`id`, `user_id`, `descricao`, `valor`, `data`, `informacao`, `grupo_id`) VALUES
(2, 2, 'Faculdade', 290.00, '2024-06-20', '', NULL),
(4, 2, 'Veículo', 290.00, '2024-06-07', 'Moto', NULL),
(5, 2, 'Emprestimo', 177.00, '2024-06-07', 'Cheque especial do cartão', NULL),
(6, 2, 'Cartão de Credito', 50.00, '2024-06-07', 'Cartão iti Itau', NULL),
(7, 2, 'Cartão de Credito', 100.00, '2024-06-02', 'Cartão Santander', NULL),
(8, 2, 'Carro', 250.00, '2024-06-07', 'Carro gabriel', NULL),
(9, 2, 'Aluguel', 350.00, '2024-06-07', 'Valor do aluguel e custo da casa divido.', NULL),
(10, 2, 'Outro', 90.00, '2024-06-07', 'Boleto Shopee', NULL),
(11, 13, 'Cartão de Credito', 10000.00, '2024-06-02', 'Puta', NULL),
(12, NULL, 'Aluguel', 350.00, '2024-06-02', NULL, 1),
(14, 22, 'Aluguel', 123.00, '2024-06-02', 'internett', NULL),
(15, 22, 'Água', 2134.00, '2024-06-02', '', NULL),
(16, 25, 'Aluguel', 1000.00, '2024-06-03', 'Aluguel casa', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `criador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `grupos`
--

INSERT INTO `grupos` (`id`, `nome`, `descricao`, `criador_id`) VALUES
(1, 'CASA', 'TESTE', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(16) NOT NULL,
  `nome` varchar(140) DEFAULT NULL,
  `email` varchar(140) NOT NULL,
  `senha` varchar(16) NOT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `salario_liquido` decimal(10,2) DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `perfil_completo` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `cpf`, `data_nascimento`, `salario_liquido`, `biografia`, `descricao`, `foto`, `perfil_completo`) VALUES
(2, 'Kauan Lauer', 'erickkauanlauer@gmail.com', '123456', '123456789', '1998-08-24', 1900.00, 'Criador desse site', 'teste para descrição de perfil', '665d07b148f15-665d0692ba457-61U0FIe2itL._AC_UF894,1000_QL80_.jpg', 1),
(8, 'Alex kaue', 'laueralex765@gmail.com', 'alex3.', '', '0000-00-00', 1620.00, '', '', NULL, 0),
(13, 'carlos rodrigues', 'carlos@gmail.com', '1234', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(22, 'bruno', 'egel@gmail.com', '1234', '1234566890', '0000-00-00', 1222.00, '', '', '665d081e2faa7-download.jpeg', 1),
(25, 'Matheus Albuquerque ', 'matheus.albuquerque@manupackaging.com.br', 'Teste12345', '', '0000-00-00', 0.00, '', '', '665e1cf346b45-user.png', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_grupo`
--

CREATE TABLE `usuario_grupo` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `despesas`
--
ALTER TABLE `despesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupo_id` (`grupo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `criador_id` (`criador_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `despesas`
--
ALTER TABLE `despesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `despesas`
--
ALTER TABLE `despesas`
  ADD CONSTRAINT `despesas_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `despesas_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`criador_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  ADD CONSTRAINT `usuario_grupo_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuario_grupo_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
