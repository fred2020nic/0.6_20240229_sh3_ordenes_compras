-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 29-02-2024 a las 18:52:46
-- Versión del servidor: 10.11.7-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u363832898_orden_compra`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_list`
--

CREATE TABLE `item_list` (
  `id` int(11) NOT NULL,
  `product_key` varchar(250) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `delivery_time` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `art` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `item_list`
--

INSERT INTO `item_list` (`id`, `product_key`, `supplier_id`, `quantity`, `unit`, `description`, `delivery_time`, `department`, `art`, `date_created`) VALUES
(2, 'Crema para cara Delia Azul', 0, 0, '0', 'Crema de limpieza profunda con suero facial y nutrientes.', 0, '', '', '2021-09-08 10:21:42'),
(3, 'Camiseta Baluga Personalizables', 0, 0, '0', 'Son las mejores camisetas con las mejores telas del mercado a precios muy econ&oacute;micas', 0, '', '', '2021-09-08 10:22:10'),
(4, 'Tapabocas Endistol 1105', 1, 0, '0', 'Tapabocas de alta calidad, con registro INVIMA 13241 Apta para uso m&eacute;dico', 0, '', '', '2021-09-09 15:21:46'),
(6, 'Tubo PPR', 0, 0, '0', 'PPRR 111', 0, '', '', '2023-06-07 21:47:38'),
(8, 'Coca Cola ', 0, 0, '0', '3litros', 0, '', '', '2023-06-27 19:42:17'),
(9, 'Papas Fritas', 0, 0, '0', 'Familiar ', 0, '', '', '2023-06-27 19:42:38'),
(11, 'Salsas Fruco ', 0, 0, '0', '', 0, '', '', '2023-06-27 19:43:26'),
(12, 'laptop', 0, 0, '0', 'laptop', 0, '', '', '2023-07-14 17:52:12'),
(13, 'sssss', 0, 0, '0', 'vdfdwd', 0, '', '', '2023-08-03 21:51:53'),
(14, 'prueba', 0, 1, '1', 'kg', 2, '', '', '2023-08-22 21:35:31'),
(15, 'ass', 0, 1, '1', 'kg', 1, '001', '001', '2023-08-22 21:41:43'),
(16, 'prueba clave', 0, 1, '1', 'producto en kg', 10, '002', '010', '2023-08-22 22:34:41'),
(17, 'solución de limpieza', 6, 2, 'caja', '(con 9 litros)', 2, 'Producción', '001', '2023-08-26 17:02:32'),
(18, 'xxx', 3, 2, 'xxx', 'xxx', 2, 'xxx', 'xxxx', '2023-08-26 17:54:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `unit_price` float NOT NULL,
  `quantity` float NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`id`, `po_id`, `item_id`, `unit_price`, `quantity`, `supplier_id`) VALUES
(53, 59, 18, 100, 2, 3),
(61, 60, 18, 100, 3, 3),
(62, 60, 17, 50, 2, 6),
(63, 62, 18, 100, 3, 3),
(64, 62, 17, 50, 2, 6),
(67, 63, 18, 100, 3, 3),
(68, 63, 17, 50, 2, 6),
(69, 64, 18, 100, 3, 3),
(70, 64, 17, 50, 2, 6),
(71, 65, 18, 100, 3, 3),
(72, 65, 17, 50, 2, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postal_codes`
--

CREATE TABLE `postal_codes` (
  `id` int(11) NOT NULL,
  `postal_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `postal_codes`
--

INSERT INTO `postal_codes` (`id`, `postal_code`) VALUES
(1, 123456),
(2, 123457),
(3, 12345),
(4, 1011546),
(5, 551400),
(6, 87777777);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `po_list`
--

CREATE TABLE `po_list` (
  `id` int(11) NOT NULL,
  `po_no` varchar(100) NOT NULL,
  `req_id` int(11) NOT NULL,
  `adress` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `discount_amount` float NOT NULL,
  `notes` text NOT NULL,
  `date_created` date NOT NULL,
  `date_updated` datetime DEFAULT current_timestamp(),
  `way_pay` varchar(100) NOT NULL,
  `invoice` varchar(100) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `seller_name` varchar(255) NOT NULL,
  `iva_percentage` int(11) NOT NULL DEFAULT 0,
  `iva_amount` int(11) NOT NULL DEFAULT 0,
  `total_supplier_discount` int(11) NOT NULL DEFAULT 0,
  `isr_percentage` int(11) NOT NULL DEFAULT 0,
  `isr_amount` int(11) NOT NULL DEFAULT 0,
  `isr_iva` int(11) NOT NULL DEFAULT 0,
  `isr_iva_amount` int(11) NOT NULL DEFAULT 0,
  `currency` varchar(100) NOT NULL,
  `order_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `po_list`
--

INSERT INTO `po_list` (`id`, `po_no`, `req_id`, `adress`, `department`, `discount_amount`, `notes`, `date_created`, `date_updated`, `way_pay`, `invoice`, `author_name`, `seller_name`, `iva_percentage`, `iva_amount`, `total_supplier_discount`, `isr_percentage`, `isr_amount`, `isr_iva`, `isr_iva_amount`, `currency`, `order_status`) VALUES
(59, 'PO-695027146906', 10, 'calle maple #103-22', 'weswes', 20, 'ENTREGAR EN BODEGA EN LA NOCHE', '2023-09-10', '2023-09-10 22:39:33', 'efectivo', 'N/A', 'alisson', '', 16, 32, 10, 5, 10, 5, 10, 'EU', 1),
(60, 'PO-361462182700', 11, 'calle maple #103-22', 'weswes', 60, 'ENTREGA EN BODEGA', '2023-09-11', '2023-09-10 22:56:51', 'cedito', 'N/A', 'xxxxssss', '', 16, 64, 15, 5, 20, 5, 20, 'MX', 1),
(62, 'PO-119656864524', 11, '', 'weswes', 60, 'asasas', '2023-09-27', '2023-09-27 22:36:34', 'cedito', 'N/A', 'amamamama', 'asasasas', 16, 64, 15, 5, 20, 0, 0, 'USD', 1),
(63, 'PO-67502478699', 11, '', 'weswes', 20, 'qweqweqwe', '2023-09-27', '2023-09-27 23:55:37', 'cedito', 'N/A', 'qwdd', 'wqeqwe', 16, 64, 5, 5, 20, 0, 0, 'USD', 1),
(64, 'PO-397974965604', 11, 'calle maple #103-22', 'weswes', 100, 'notas prueba', '2023-09-28', '2023-09-28 00:03:30', 'efectivo', 'N/A', 'asdassds', 'asdasdasds', 16, 64, 25, 5, 20, 0, 0, 'EU', 1),
(65, 'PO-119749920539', 11, 'calle maple #103-22', 'weswes', 100, 'Al contrario del pensamiento popular, el texto de Lorem Ipsum no es simplemente texto aleatorio. Tiene sus raices en una pieza cl´sica de la literatura del Latin, que data del año 45 antes de Cristo, haciendo que este adquiera mas de 2000 años de antiguedad. Richard McClintock, un profesor de Latin de la Universidad de Hampden-Sydney en Virginia, encontró una de las palabras más oscuras de la lengua del latín, \"consecteur\", en un pasaje de Lorem Ipsum, y al seguir leyendo distintos textos del latín, descubrió la fuente indudable. Lorem Ipsum viene de las secciones 1.10.32 y 1.10.33 de \"de Finnibus Bonorum et Malorum\" (Los Extremos del Bien y El Mal) por Cicero, escrito en el año 45 antes de Cristo. Este libro es un tratado de teoría de éticas, muy popular durante el Renacimiento. La primera linea del Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", viene de una linea en la sección 1.10.32\r\n\r\nEl trozo de texto estándar de Lorem Ipsum usado desde el año 1500 es reproducido debajo para aquellos interesados. Las secciones 1.10.32 y 1.10.33 de \"de Finibus Bonorum et Malorum\" por Cicero son también reproducidas en su forma original exacta, acompañadas por versiones en Inglés de la traducción realizada en 1914 por H. Rackham.', '2023-09-28', '2023-09-28 00:09:28', 'cedito', 'N/A', 'alisson', 'carlos', 16, 64, 25, 6, 24, 0, 0, 'MX', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `req_items`
--

CREATE TABLE `req_items` (
  `id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `req_items`
--

INSERT INTO `req_items` (`id`, `req_id`, `item_id`, `unit_price`, `quantity`, `supplier_id`) VALUES
(71, 10, 18, 100, 2, 3),
(74, 11, 18, 100, 3, 3),
(75, 11, 17, 50, 2, 6),
(76, 12, 0, 50, 100, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `req_list`
--

CREATE TABLE `req_list` (
  `id` int(11) NOT NULL,
  `date_created` date NOT NULL,
  `date_update` datetime DEFAULT current_timestamp(),
  `author_name` varchar(100) NOT NULL,
  `credit_status` int(11) NOT NULL,
  `type_order` varchar(100) NOT NULL,
  `counted` varchar(100) NOT NULL,
  `iva_percentage` int(11) NOT NULL,
  `iva_amount` int(11) NOT NULL,
  `isr_percentage` int(11) NOT NULL,
  `isr_amount` int(11) NOT NULL,
  `observation` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL,
  `invoice` varchar(100) NOT NULL,
  `way_pay` varchar(100) NOT NULL,
  `client_invoice` varchar(100) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `bank` varchar(100) NOT NULL,
  `client_key` varchar(100) NOT NULL,
  `client_account_num` int(11) NOT NULL,
  `client_card_num` int(11) NOT NULL,
  `branch_office` varchar(100) NOT NULL,
  `pr_no` varchar(100) NOT NULL,
  `contact_client` varchar(50) NOT NULL,
  `email_client` varchar(255) NOT NULL,
  `dias_c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `req_list`
--

INSERT INTO `req_list` (`id`, `date_created`, `date_update`, `author_name`, `credit_status`, `type_order`, `counted`, `iva_percentage`, `iva_amount`, `isr_percentage`, `isr_amount`, `observation`, `department`, `invoice`, `way_pay`, `client_invoice`, `client_name`, `bank`, `client_key`, `client_account_num`, `client_card_num`, `branch_office`, `pr_no`, `contact_client`, `email_client`, `dias_c`) VALUES
(10, '2023-08-28', '2023-08-28 01:06:32', 'alisson', 1, 'pedidp prueba', 'N/A', 10, 20, 5, 10, 'prueba', 'weswes', 'N/A', 'efectivo', 'N/A', 'fulanito', 'bbc', '123456', 123344, 987456, 'centro', 'REQ-08726430560', '32111111111111', 'EXAMPLE@gmail.com', 0),
(11, '2023-09-09', '2023-09-09 09:20:09', 'xxxx', 1, 'pedidp prueba', 'prueb', 16, 64, 5, 20, '', 'weswes', 'N/A', 'cedito', 'N/A', 'fulanito', 'bbc', '123456', 123344, 987456, 'centro', 'REQ-795183586857', '32111111111111', 'EXAMPLE@gmail.com', 0),
(12, '2024-02-07', '2024-02-07 20:09:52', 'Freddy', 0, '1200', '1000', 16, 800, 0, 0, '', 'Informatica', '12', '', '', '', '', '', 0, 0, '', 'REQ-784576927475', '', '', 0),
(13, '2024-02-07', '2024-02-07 20:22:08', 'Freddy', 1, '1200', '0', 16, 192, 0, 0, 'varios', 'Informatica', '', '', '', '', '', '', 0, 0, '', 'REQ-501563820107', '', '', 0),
(14, '2024-02-07', '2024-02-07 20:27:20', 'Freddy', 1, '1200', '0', 16, 1792, 0, 0, 'varios', 'Informatica', '12', '', '', '', '', '', 0, 0, '', 'REQ-250629033286', '', '', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier_list`
--

CREATE TABLE `supplier_list` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `postal_code_id` int(11) NOT NULL,
  `suburb` varchar(100) NOT NULL,
  `town_hall` varchar(255) NOT NULL,
  `contact_sold` varchar(50) NOT NULL,
  `contact_invoice` varchar(50) NOT NULL,
  `contact_pay` varchar(50) NOT NULL,
  `discount` int(11) NOT NULL,
  `type_person` varchar(50) NOT NULL,
  `credit` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `dias_c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `supplier_list`
--

INSERT INTO `supplier_list` (`id`, `name`, `business_name`, `postal_code_id`, `suburb`, `town_hall`, `contact_sold`, `contact_invoice`, `contact_pay`, `discount`, `type_person`, `credit`, `email`, `date_created`, `dias_c`) VALUES
(1, 'Pesas y Pesas García', '', 0, '', '', 'Rafael Pérez', '3122874659', '', 0, '', 0, 'rperez@cweb.com', '2021-09-08 09:46:45', 0),
(3, 'Tapabocas  Capabol', '', 0, '', '', '3152587412', '3012584651', '', 10, '', 0, 'admin@tapacapabol.com', '2021-09-09 15:20:32', 0),
(6, 'Licores JL', '', 0, '', '', 'Santiago Lopez Ramirez', '3028580166', '', 5, '', 0, 'santiago@gerente.com', '2023-06-27 19:46:59', 0),
(8, 'INTCOMEX DE GUATEMALA, S.A.', 'INTCOMEX DE GUATEMALA, S.A.', 3, 'colonia nueva', 'ejemplo alcaldia', 'EDGAR QUINTEROS', 'jc 3222222', '3212222', 0, 'fisica', 0, 'equinteros@gmail.com', '2023-07-14 17:51:52', 0),
(12, 'Prueba inical', 'PR INICIAL', 3, 'colonia 1', 'alcaldia 1', 'daniel: 555555', 'xxx: 55555', 'llll: 888888', 25, 'fisica', 0, 'aaaa@gmail.com', '2023-08-23 02:57:18', 0),
(14, 'Freddy Eden', '123456', 3, '12', '12', '12', '12', '12', 12, 'fisica', 1, 'admin@admin.com', '2024-02-07 20:04:33', 100),
(15, 'Mauricio', 'asda', 1, '12', '12', '12', '12', '12', 0, 'fisica', 1, 'hola@configuroweb.com', '2024-02-07 21:00:11', 10000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_info`
--

CREATE TABLE `system_info` (
  `id` int(11) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', ''),
(6, 'short_name', 'GCO'),
(11, 'logo', 'uploads/1693957380_ICONO ALVARTIS.jpg'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/1693957380_fondo3.jpg'),
(15, 'company_name', 'ALVARTIS PHARMA S.A. DE C.V.'),
(16, 'company_email', 'hola@gestiondeordenes.com'),
(17, 'company_address', 'Calle 78 N 24 32'),
(18, 'company_name', 'ALVARTIS PHARMA S.A. DE C.V.'),
(19, 'company_email', 'hola@gestiondeordenes.com'),
(20, 'company_address', 'Calle 78 N 24 32'),
(21, 'cover', 'uploads/1693957380_fondo3.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tax_records`
--

CREATE TABLE `tax_records` (
  `id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `name_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tax_records`
--

INSERT INTO `tax_records` (`id`, `req_id`, `name_file`) VALUES
(10, 6, '64ec2f5074df7.pdf'),
(11, 7, '64ec312089904.pdf'),
(12, 7, '64ec31208a79f.pdf'),
(14, 9, '64ec35c6ba068.pdf'),
(16, 10, '64ec3968dd7ed.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Mauricio', 'Sevilla', 'configuroweb', '4b67deeb9aba04a5b54632ad19934f26', 'uploads/1631295840_logo youtube.png', NULL, 1, '2021-01-20 14:02:37', '2021-09-10 12:44:39'),
(3, 'Juan', 'Operador', 'joperador', '4b67deeb9aba04a5b54632ad19934f26', 'uploads/1631219220_avatar1.jpg', NULL, 2, '2021-09-07 15:20:40', '2021-09-09 15:27:37'),
(5, 'Sheyla', 'S', 'sheyla@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'uploads/1701102240_CW.jpg', NULL, 1, '2023-11-27 16:24:28', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `item_list`
--
ALTER TABLE `item_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `item_no` (`item_id`);

--
-- Indices de la tabla `postal_codes`
--
ALTER TABLE `postal_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `po_list`
--
ALTER TABLE `po_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `req_items`
--
ALTER TABLE `req_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `req_id` (`req_id`);

--
-- Indices de la tabla `req_list`
--
ALTER TABLE `req_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tax_records`
--
ALTER TABLE `tax_records`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `item_list`
--
ALTER TABLE `item_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `postal_codes`
--
ALTER TABLE `postal_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `po_list`
--
ALTER TABLE `po_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `req_items`
--
ALTER TABLE `req_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `req_list`
--
ALTER TABLE `req_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `tax_records`
--
ALTER TABLE `tax_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`po_id`) REFERENCES `po_list` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `req_items`
--
ALTER TABLE `req_items`
  ADD CONSTRAINT `req_items_ibfk_1` FOREIGN KEY (`req_id`) REFERENCES `req_list` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
