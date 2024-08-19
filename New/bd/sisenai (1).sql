-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 10.21.1.69
-- Tempo de geração: 07/06/2024 às 22:36
-- Versão do servidor: 8.0.36-0ubuntu0.22.04.1
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sisenai`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `curso_id` int NOT NULL,
  `nome_curso` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `area_tecnologica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ano` year NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`curso_id`, `nome_curso`, `area_tecnologica`, `ano`) VALUES
(39, 'CIP - NR 35 CAPACITAÇÃO PARA TRABALHO EM ALTURA', 'senai', '2024'),
(40, 'CIP - NR 35 CAPACITAÇÃO PARA TRABALHO EM ALTURA', 'senai', '2024'),
(41, 'CQP - ASSISTENTE ADMINISTRATIVO', 'senai', '2024'),
(42, 'CQP - ASSISTENTE DE CONTABILIDADE', 'senai', '2024'),
(43, 'CQP - ASSISTENTE DE OPERAÇÕES LOGÍSTICA', 'senai', '2024'),
(44, 'CQP - ASSISTENTE DE RECURSOS HUMANOS', 'senai', '2024'),
(45, 'CQP - ASSISTENTE DE RECURSOS HUMANOS', 'senai', '2024'),
(46, 'CQP - AUXILIAR DE LAB. QUÍMICO E MICROBIOLÓGICO', 'senai', '2024'),
(47, 'CQP - CONTROLADORES LÓG. PROG. E SUPERVISÓRIOS', 'senai', '2024'),
(48, 'CQP - ELETRICISTA INDUSTRIAL', 'senai', '2024'),
(49, 'CQP - ELETRICISTA INSTALADOR RESIDENCIAL', 'senai', '2024'),
(50, 'CQP - INST E REPAR DE REDES DE COMPUTADORES', 'senai', '2024'),
(51, 'CQP - MECÂNICO DE FREIOS, SUSP. E DIR. DE VEÍC. LEVES', 'senai', '2024'),
(52, 'CQP - MECÂNICO DE MANUT. EM MOTORES CICLO OTTO', 'senai', '2024'),
(53, 'CQP - MECÂNICO DE MANUTENÇÃO EM MOTOCICLETAS ', 'senai', '2024'),
(54, 'CQP - OPERADOR DE COMPUTADOR', 'senai', '2024'),
(55, 'CAI - OPERADOR DE PROD. DE ALCOOL - FAZENDÃO', 'SENAI', '2024'),
(56, 'REUNIÃO FIETO', 'senai', '2024'),
(57, 'REUNIÃO SENALBA', 'senai', '2024'),
(58, 'CAI BAS - ASSISTENTE ADMINISTRATIVO - 1040 HS', 'SENAI', '2024'),
(59, 'AVALIAÇÃO SAEP', 'SENAI', '2024'),
(61, 'SIMULADO SAEP ', 'senai', '2024'),
(62, 'CAI BAS - ASSISTENTE ADMINISTRATIVO - CORREIOS', 'SENAI', '2024'),
(63, 'CAP - ALINHAMENTO E BALANCEAMENTO AUTOMOTIVO', 'SENAI', '2024'),
(64, 'CHP - TÉC. EM INFORMÁTICA', 'SENAI', '2024'),
(65, 'CAP - COMANDOS ELÉTRICOS', 'SENAI', '2024'),
(66, 'CAP - ELETRÕNICA ANALÓGICA', 'SENAI', '2024'),
(67, 'CHP - TÉC. EM ELETROTÉCNICA', 'SENAI', '2024'),
(68, 'CAP - EXCEL AVANÇADO', 'SENAI', '2024'),
(69, 'CAP - EXCEL BÁSICO', 'SENAI', '2024'),
(70, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 'SENAI', '2024'),
(71, 'CAP - INSTALADOR DE SIST. FOTOVOLTÁCO - ON GRID', 'SENAI', '2024'),
(72, 'CHP - TÉC. EM MECATRÔNICA', 'SENAI', '2024'),
(73, 'CAP - RECICLAGEM NR 10 BÁSICO', 'SENAI', '2024'),
(74, 'CAP - RECICLAGEM NR 10 SEP', 'SENAI', '2024'),
(75, 'CHP - TÉC. EM QUÍMICA', 'SENAI', '2024'),
(76, 'CHP - TÉC. EM AÇÚCAR E ÁLCOOL', 'SENAI', '2024'),
(77, 'CHP SEMI - TÉC. EM AUTOMAÇÃO INDUSTRIAL', 'SENAI', '2024'),
(78, 'CHP - TÉC. EM ADMINISTRAÇÃO', 'SENAI', '2024'),
(79, 'CHP SEMI - TÉC. EM ELETROTÉNICA', 'SENAI', '2024'),
(80, 'CHP - TÉC. EM AUTOMAÇÃO INDUSTRIAL', 'SENAI', '2024'),
(81, 'CHP - TÉC. EM DESENVOLVIMENTO DE SISTEMAS', 'SENAI', '2024'),
(82, 'CHP SEMI - TÉC. EM LOGÍSTICA', 'SENAI', '2024'),
(83, 'CQP SEMI - ASSISTENTE ADMINISTRATIVO', 'SENAI', '2024'),
(84, 'CHP SEMI - TÉC. EM QUÍMICA - GARNOL', 'SENAI', '2024'),
(85, 'CQP SEMI - ASSISTENTE DE CONTROLE DA QUALIDADE', 'SENAI', '2024'),
(86, 'CHP SEMI - TÉC. EM REDES DE COMPUTADORES', 'SENAI', '2024'),
(87, 'CQP SEMI - ASSISTENTE DE OPERAÇÕES LOGÍSTICAS', 'SENAI', '2024'),
(88, 'CHP SEMI - TÉC. EM SEGURANÇA DO TRABALHO', 'SENAI', '2024'),
(89, 'CQP SEMI - ASSISTENTE DE RECURSOS HUMANOS', 'SENAI', '2024'),
(90, 'CIP - BÁSICO EM ELETRICIDADE RESIDENCIAL', 'SENAI', '2024'),
(91, 'CIP - NR 10 BÁSICA', 'SENAI', '2024'),
(92, 'CIP - NR 11 E NR 12 - OPERAÇÃO DE EMPILHADEIRA', 'SENAI', '2024'),
(93, 'CQP SEMI - MONTADOR E REPARADOR DE COMPUTADOR', 'SENAI', '2024'),
(94, 'CQP SEMI - OPERADOR DE COMPUTADOR', 'SENAI', '2024'),
(95, 'PROCESSO SELETIVO', 'SENAI', '2024'),
(96, 'PROJETO TOCANTINS MAIS PRODUTIVO', 'SENAI', '2024'),
(97, 'CIP - SOLDAGEM DE MANUTENÇÃO DO ARCO ELÉTRICO', 'SENAI', '2024'),
(101, 'CHP - TÉC. EM INFORMÁTICA', 'senai', '2024'),
(102, 'CIP - SOLDAGEM DE MANUTENÇÃO DO ARCO ELÉTRICO', 'SENAI', '2024');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `professor_id` int NOT NULL,
  `nome_professor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professores`
--

INSERT INTO `professores` (`professor_id`, `nome_professor`, `email`, `telefone`) VALUES
(34, 'Janety Silva', 'mail@mail.com', '99999999999'),
(35, 'Lara Guida', 'mail@mail.com', '99999999999'),
(36, 'Thiago Silva', 'mail@mail.com', '99999999999'),
(37, 'Adriano Mendonça', 'mail@mail.com', '99999999991'),
(38, 'Beatriz Marinho ', 'mail@mail.com', '99999999999'),
(39, 'Breno Andrade', 'mail@mail.com', '99999999999'),
(40, 'Daniel Santana', 'mail@mail.com', '99999999999'),
(41, 'Nairann Martins', 'mail@email.com', '9999999999'),
(42, 'Domingos Miranda', 'mail@mail.com', '99999999999'),
(43, 'Edson Pereira', 'mail@mail.com', '99999999999'),
(44, 'Érico Veríssimo', 'mail@mail.com', '99999999999'),
(45, 'Omara Braga', 'mail@email.com', '9999999999'),
(46, 'Francine Batista', 'mail@mail.com', '99999999999'),
(47, 'Gustavo Henrique', 'mail@mail.com', '99999999999'),
(48, 'Hilquias Hakylla', 'mail@mail.com', '99999999999'),
(49, 'Patrick Allan', 'mail@email.com', '9999999999'),
(50, 'José Moreira', 'mail@mail.com', '99999999999'),
(51, 'Richard Possel', 'mail@email.com', '9999999999'),
(52, 'Juliano Rufino ', 'mail@mail.com', '99999999999'),
(53, 'Lucas Silva', 'mail@mail.com', '99999999999'),
(54, 'Luís Eduardo', 'mail@mail.com', '99999999999'),
(55, 'Richard Ramonn', 'mail@email.com', '9999999999'),
(56, 'Luís Otávio', 'mail@mail.com', '99999999999'),
(57, 'Silvio Oliveira', 'mail@email.com', '9999999999'),
(58, 'Valeria silva', 'mail@email.com', '9999999999'),
(59, 'Bruniely', 'mail@email.com', '9999999999'),
(60, 'Bruniely Brito', 'mail@email.com', '9999999999'),
(61, 'Edir Santos', 'mail@email.com', '9999999999'),
(62, 'Emanoela Hora', 'mail@email.com', '9999999999'),
(63, 'Hiago Costa', 'mail@email.com', '9999999999'),
(64, 'Josimar Souza', 'mail@email.com', '9999999999'),
(65, 'Pedro Almeda', 'mail@email.com', '9999999999'),
(66, 'Ruan Rodrigues', 'mail@email.com', '9999999999'),
(69, 'BRUNINNNN', 'bruninroduigues@gmail.com', '(63) 992915566'),
(72, 'daniel', 'mail@email.com', '9999999999');

-- --------------------------------------------------------

--
-- Estrutura para tabela `salas`
--

CREATE TABLE `salas` (
  `sala_id` int NOT NULL,
  `nome_sala` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `andar` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `salas`
--

INSERT INTO `salas` (`sala_id`, `nome_sala`, `andar`) VALUES
(22, 'SUB-LAB. AUTOMOTIVA 2', 'Subsolo'),
(23, '112-MECÂNICA', 'Térro'),
(24, '113-COMANDOS', '1º Andar'),
(26, '114-ELETRÔNICA', '1º Andar'),
(27, '201-MICROBIOLOGIA', '2º Andar'),
(28, '012-LAB. AUTOMOTIVA 1', 'Térro'),
(29, '101-SALA DE AULA', '1º Andar'),
(30, '102-SALA DE AULA', '1º Andar'),
(31, '103-LOGÍSTICA', '1º Andar'),
(32, '104-SALA DE AULA', '1º Andar'),
(33, '105-SALA DE AULA', '1º Andar'),
(34, '106-SALA DE AULA', '1º Andar'),
(35, '202-SENAI LAB.', '2º Andar'),
(36, '107-CLP', '1º Andar'),
(37, '203-QUÍMICA', '2º Andar'),
(38, '108-ELETR. RESIDENCIAL', '1º Andar'),
(39, '204-INFOR. EAD', '2º Andar'),
(40, '109-MICRODESTILARIA', '1º Andar'),
(41, '110-HIDRÁULICA E PNEUM.', '1º Andar'),
(42, '206-SALA DE AULA', '2º Andar'),
(43, '111-COMANDOS', '1º Andar'),
(44, '301-MANUT. MICRO', '3º Andar'),
(45, '302-DESIGN GRÁFICO', '3º Andar'),
(46, '303-INFOR. REDES', '3º Andar'),
(47, '304-INFOR. TI 01', '3º Andar'),
(48, '305-INFOR. TI 02', '3º Andar'),
(49, '306-INFOR. TI 03', '3º Andar'),
(50, '307-SOLDA', '3º Andar');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `turma_id` int NOT NULL,
  `nome_turma` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `curso_id` int DEFAULT NULL,
  `sala_id` int DEFAULT NULL,
  `professor_id` int DEFAULT NULL,
  `horario_inicio` time NOT NULL,
  `horario_final` time NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `dias_aula` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`turma_id`, `nome_turma`, `curso_id`, `sala_id`, `professor_id`, `horario_inicio`, `horario_final`, `data_inicio`, `data_fim`, `dias_aula`) VALUES
(35, 'TEC.2023.2.226', 70, 29, 41, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(36, 'TEC 2022.2.207', 67, 26, 66, '19:00:00', '22:00:00', '2024-07-20', '2025-09-21', 'segunda,terca,quarta,quinta,sexta'),
(37, 'TEC.2024.1.239', 78, 33, 52, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(38, 'API.2024.103', 55, 33, 53, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(39, 'QUA.2024.188', 49, 36, 42, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,sexta'),
(40, 'TEC.2022.2.205', 70, 33, 48, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(41, 'TEC.2023.1.223', 79, 36, 54, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(42, 'TEC.2022.2.208', 77, 35, 37, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(43, 'QUA.2024.293', 54, 39, 59, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(44, 'TEC.2024.1.241', 70, 34, 43, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(45, 'QUA.2024.036/039', 53, 40, 64, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'sexta'),
(46, 'TEC.2023.2.232', 75, 37, 46, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'sexta'),
(47, 'TEC.2024.1.243', 76, 39, 60, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'quinta,sexta'),
(48, 'TEC.2023.2.232', 75, 37, 46, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'sexta'),
(49, 'TEC.2023.2.229', 64, 46, 63, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(51, 'TEC.2024.1.238', 64, 39, 44, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda'),
(52, 'TEC.2024.1.236', 75, 42, 51, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta'),
(53, 'TEC.2024.1.237', 81, 45, 39, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'quarta'),
(54, 'API.2023.118', 62, 42, 45, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(55, 'QUA.2024.150', 83, 39, 53, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'terca,quarta,quinta'),
(56, 'QUA.2024.084', 89, 39, 53, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'sexta'),
(57, 'TEC.2023.1.216', 64, 48, 40, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(58, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 47, '14:00:00', '18:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta'),
(59, 'TEC.2024.1.245', 78, 48, 52, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(60, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 47, '13:00:00', '16:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta'),
(61, 'QUA.2024.096', 94, 49, 44, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(62, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 47, '14:00:00', '18:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta'),
(63, 'TEC.2024.1.245', 77, 48, 58, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'segunda,terca'),
(64, 'TEC.2023.2.232', 75, 45, 46, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'terca'),
(65, 'TEC.2024.1.242', 80, 50, 56, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'quinta,sexta'),
(66, 'TEC.2022.2.214', 84, 45, 46, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'sexta'),
(67, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 38, '14:00:00', '18:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta,quinta'),
(68, 'API.108', 58, 49, 60, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta'),
(69, 'TEC.2023.2.228', 67, 24, 39, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(70, 'TEC.2023.2.232', 75, 27, 46, '14:00:00', '17:00:00', '2023-02-10', '2024-05-10', 'sexta'),
(71, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 38, '14:00:00', '18:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta,quinta'),
(72, 'TEC.2024.1.239', 78, 45, 52, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda'),
(73, 'TEC.2024.1.244', 86, 49, 60, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'quinta,sexta'),
(74, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 38, '14:00:00', '18:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta,quinta'),
(75, 'QUA.2024.036/039', 53, 24, 64, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta'),
(76, 'TEC.2023.2.232', 75, 27, 46, '14:00:00', '18:00:00', '2024-04-10', '2024-09-11', 'sexta'),
(77, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 38, '14:00:00', '18:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta,quinta'),
(78, 'TEC.2023.2.225', 67, 24, 42, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(79, 'TEC.2022.2.215', 80, 35, 37, '14:00:00', '18:00:00', '2023-02-13', '2024-08-10', 'quinta'),
(80, ' CHP-TEC.EM MECATRÔNICA', 72, 41, 38, '14:00:00', '18:00:00', '2024-06-01', '2024-06-30', 'segunda,terca,quarta,quinta'),
(81, 'INI.2024.035', 97, 50, 50, '19:00:00', '22:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(82, 'QUA.2024.293', 54, 47, 60, '14:00:00', '18:00:00', '2023-02-16', '2024-03-17', 'segunda,terca,quarta'),
(83, 'TEC.2024.1.237', 81, 26, 39, '14:00:00', '18:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta'),
(84, 'TEC.2023.2.230', 72, 47, 47, '14:00:00', '18:00:00', '2023-02-23', '2024-08-17', 'quinta,sexta'),
(86, 'CHP - TÉC. EM DESENVOLVIMENTO DE SISTEMAS', 81, 43, 39, '07:00:00', '12:00:00', '2024-06-01', '2026-06-01', 'sexta'),
(87, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '17:00:00', '20:00:00', '2024-06-30', '2025-06-07', 'segunda,terca,quarta,quinta,sexta'),
(88, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '13:00:00', '17:00:00', '2024-06-16', '2025-08-27', 'segunda,terca,quarta,quinta,sexta'),
(89, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '16:24:00', '20:44:00', '2024-06-08', '2025-08-30', 'segunda,terca,quarta,quinta,sexta'),
(90, 'TEC.2024.1.238', 64, 47, 44, '08:00:00', '12:00:00', '2024-02-01', '2024-12-20', 'segunda,terca,quarta,quinta,sexta'),
(91, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '22:00:00', '11:00:00', '2024-06-29', '2025-08-29', 'segunda,terca,quarta,quinta,sexta'),
(92, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '16:47:00', '21:47:00', '2024-06-01', '2026-11-29', 'segunda,terca,quarta,quinta,sexta'),
(93, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '17:48:00', '21:48:00', '2024-05-31', '2025-10-30', 'segunda,terca,quarta,quinta,sexta'),
(94, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '13:00:00', '17:00:00', '2024-06-22', '2026-06-28', 'segunda,terca,quarta,quinta,sexta'),
(95, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '17:50:00', '21:50:00', '2024-06-01', '2027-06-07', 'segunda,terca,quarta,quinta,sexta'),
(96, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '08:51:00', '13:51:00', '2024-06-29', '2028-12-07', 'segunda,terca,quarta,quinta,sexta'),
(97, 'CHP - TÉC. EM MANUTENÇÃO AUTOMOTIVA', 70, 23, 57, '20:52:00', '22:52:00', '2024-06-08', '2025-06-30', 'segunda,terca,quarta,quinta,sexta'),
(99, 'CHP - TÉC. EM INFORMÁTICA', 64, 46, 40, '14:00:00', '18:00:00', '2024-06-01', '2024-09-07', 'segunda,terca,quarta,quinta,sexta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int NOT NULL,
  `nome_usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_usuario` enum('admin','professor','aluno') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nome_usuario`, `email`, `senha`, `tipo_usuario`) VALUES
(10, 'maria', 'maria@senai.com.br', '$2y$10$IQCMLKzomorewAOQWFWb8.moX97AB0utC2mOSaEqKSKkIHqOVCdaC', 'admin'),
(11, 'Jo&amp;atilde;o Luiz Fernandes de Souza', 'joao.souza103@aluno.senai.br', '$2y$10$TcD4vRS5V.7ZBvWUoa6LaeKwx7Fgkmg304s9N8L2drHstFE1Fo8Wy', 'admin'),
(12, 'tainara', 'tr30405@gmail.com', '$2y$10$FIziS74i0FZYE50u0OEvr.FYRB5QMkoC5coR/bSdLPlOmvuqadK5a', 'admin'),
(13, 'Maria Luiza Silva do Nascimento', 'marialuizanascimento2014@gmail.com', '$2y$10$TSz/kExRyYu5gD6pyrIBqeTTVXJwuyRGY428JwNjxHW3REFzVxU/G', 'admin'),
(14, 'David Carlos Silva Ramalho', 'david.ramalho@aluno.senai.br', '$2y$10$CShB42NJu0rj23zOvHgKhuLyn6SvFB4A7KBDgjftmAyRdq5MUVlq.', 'admin'),
(15, 'J&amp;uacute;lia Coelho Rodrigues', 'julia.c.rodrigues@aluno.senai.br', '$2y$10$jCwTldtrIGNtpmgEZhKSaevzGox9ldR1J5MlnDhfn.h6jhfKKl9Gi', 'admin'),
(16, 'Jo&amp;atilde;o Luiz ', 'joao.souza103@aluno.senai.br', '$2y$10$v5X2sQ00IvuxWsywIWxCb.6BloU/b5UQ66y417hUWezTJWSDXaa9.', 'admin'),
(17, 'Jo&amp;atilde;o Luiz Fernandes de Souza', 'joao.souza103@aluno.senai.br', '$2y$10$QFGLuzOEqDXBGrWOIA8QVurijMn14c/QUn02Ut8YefL1teMl2gKCO', 'admin'),
(18, 'Luiz', 'luiz@gmai.com', '$2y$10$gyDdaE2BNX3SI.JFKkGr8OWmJ9wXf5ArdIFWF5iyJzodfdErJW772', 'admin'),
(19, 'Enzo Henrique Monteiro', 'enzo.h.melo@aluno.senai.br', '$2y$10$EQhRFbQ7jSRvJWDl2xA9leHYBKcZek1DhmZ4X2hLJA8DvM.TDPZ9u', 'admin'),
(20, 'joao', 'joao.souza103@aluno.senai.br', '$2y$10$VfZVxN0HKW7RMBFk64TxWucIN/50J8ZI9I5K3sCFQn8iDHQpR5GwK', 'admin'),
(21, 'Luiz', 'luiz@gmail.com', '$2y$10$NQVAO2OQFA3zUm06w3WPFun7Qa9DcYvtHDoKNKl7J8R9QXU17sbWm', 'admin'),
(22, 'Bruno', 'bruninroduigues@gmail.com', '$2y$10$ahq4pIyuWXF2P.OmSmWKf.40nx3bbZK4olQuOiE4BNnCGTPd53mGC', 'admin'),
(23, 'David', 'david@gmail.com', '$2y$10$vgwbdCi6v.24s3AqGHmnoudhHgdvTZCGE0Y//YRmri54TDRIkhMDy', 'admin'),
(24, 'Maria Luiza', 'marialuizanascimento2014@gmail.com', '$2y$10$J8S2DSsHbjJo/fY/fWnd6.dszi59rnPuct18BgRcAnYY9GPL40vDa', 'admin'),
(25, 'Julia', 'julia.c.rodrigues@aluno.senai.br', '$2y$10$7oeTfmsUWW2sXZawrt8kB.hMv4UCyczPRSDDVFhq6PZLfwDgj7JPG', 'admin'),
(26, 'Enzo Henrique   ', 'enzo.h.melo@aluno.senai.br', '$2y$10$gbHgfxkgnwHOx8Wn1LGaee/jLA8qH3jb0zC27KTz1eO93d.hIblW.', 'admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`curso_id`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`professor_id`);

--
-- Índices de tabela `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`sala_id`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`turma_id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `sala_id` (`sala_id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `curso_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `professor_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de tabela `salas`
--
ALTER TABLE `salas`
  MODIFY `sala_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `turma_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`),
  ADD CONSTRAINT `turmas_ibfk_2` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`sala_id`),
  ADD CONSTRAINT `turmas_ibfk_3` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`professor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
