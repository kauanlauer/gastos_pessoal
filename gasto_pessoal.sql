-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/06/2024 às 01:26
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
(11, 13, 'Cartão de Credito', 10000.00, '2024-06-02', 'Puta', NULL);

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
(2, 'Kauan Lauer', 'erickkauanlauer@gmail.com', '123456', '10825732999', '1998-08-24', 1900.00, 'Criador desse site', 'teste para descrição de perfil', NULL, 1),
(8, 'Alex kaue', 'laueralex765@gmail.com', 'alex3.', '', '0000-00-00', 1620.00, '', '', NULL, 0),
(13, 'carlos rodrigues', 'carlos@gmail.com', '1234', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(15, 'Kauan Lauer', 'teste@gmail.com', '123456', '', '0000-00-00', 1900.00, '', '', NULL, 0),
(16, 'Kauan Lauer', 'teste2@gmail.com', '123456', '', '0000-00-00', 0.00, '', '', NULL, 0),
(17, 'Kauan Lauer', 'kauanlauer@gmail.com', '123456', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(18, 'teste faculdade', 'erickkaasduanlauer1@gmail.com', '123456', '123', '2024-06-02', 123123.00, 'RTWS', '123ASD', NULL, 0),
(19, 'teste', 'teste5@gmail.com', '123456', NULL, NULL, NULL, NULL, NULL, NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
