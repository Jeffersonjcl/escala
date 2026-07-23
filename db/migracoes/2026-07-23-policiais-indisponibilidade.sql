-- Migração: total de dias de indisponibilidade a contar de uma data
-- Adiciona os campos de período de indisponibilidade na tabela `policiais`.
-- Seguro para rodar em bancos já existentes.

ALTER TABLE `policiais`
  ADD COLUMN `indispo_data_inicio` date DEFAULT NULL AFTER `motivo_indispo`,
  ADD COLUMN `indispo_dias` int(11) DEFAULT NULL AFTER `indispo_data_inicio`;
