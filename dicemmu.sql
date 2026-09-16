-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 07:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dicemmu`
--

-- --------------------------------------------------------

--
-- Table structure for table `board_card`
--

CREATE TABLE `board_card` (
  `CardID` int(10) UNSIGNED NOT NULL,
  `SectionID` int(10) UNSIGNED NOT NULL,
  `UserID` smallint(5) UNSIGNED NOT NULL,
  `AuthorType` enum('PC','NPC') DEFAULT NULL,
  `AuthorID` int(10) UNSIGNED DEFAULT NULL,
  `Title` varchar(150) NOT NULL,
  `Content` varchar(1000) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `board_note`
--

CREATE TABLE `board_note` (
  `NoteID` int(10) UNSIGNED NOT NULL,
  `UserID` smallint(5) UNSIGNED NOT NULL,
  `Content` varchar(500) NOT NULL,
  `AuthorType` enum('PC','NPC','Faction','') DEFAULT NULL,
  `AuthorID` int(10) UNSIGNED DEFAULT NULL,
  `Color` varchar(20) NOT NULL DEFAULT 'yellow',
  `PosX` decimal(5,2) NOT NULL,
  `PosY` decimal(5,2) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `board_section`
--

CREATE TABLE `board_section` (
  `SectionID` int(10) UNSIGNED NOT NULL,
  `SectionName` varchar(100) NOT NULL,
  `SortOrder` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `board_section`
--

INSERT INTO `board_section` (`SectionID`, `SectionName`, `SortOrder`, `CreatedAt`) VALUES
(1, 'Guild Notice', 1, '2026-09-08 08:22:54'),
(2, 'Member Notice', 2, '2026-09-08 08:22:54'),
(3, 'Advertisement', 3, '2026-09-08 08:22:54'),
(4, 'Quests', 4, '2026-09-08 08:22:54'),
(5, 'Completed Quests', 5, '2026-09-08 08:22:54'),
(6, 'Retired or Fallen Adventurers', 6, '2026-09-08 08:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `dekkara_event`
--

CREATE TABLE `dekkara_event` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `start_day` int(11) NOT NULL,
  `end_day` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dekkara_event`
--

INSERT INTO `dekkara_event` (`id`, `name`, `Description`, `start_day`, `end_day`) VALUES
(1, 'The Hand of the Gods', 'A cleric of Zaradin, Coranar, aided by the rogue Julius, the druid Silverhawk, the sorcerer Thalamus and the paladin Adjudicator Galarion, discovered a feud among an ancient family known as the Form Wielders, a line that has abandoned their charge from the Gods to protect an ancient artifact known as the Form Staff - a powerful instrument used in the creation of the world, said to drive any mortal but the Form Wielders insane if they were to wield it. They successfully retrieved the staff from deep underground and was bringing it to the Wounded Peaks to return the staff to the Gods before they were waylaid.', 1839961, 1839961),
(2, 'Trail of the Elements', 'The party of the psychic warrior Cyle, the rogue Chorelius, the cleric of Xryvdza Cloud, the bard Chalcedony, the sorcerer Teves, the psion Saemon and the paladin Adeas Lightweaver hunted down the Form Staff as it wreaked elemental havoc across the eastern coast of Carlohn. They uncover a Temple of Elemental Evil deep within the Goldlands attempting to employ the Form Staff to free an ancient imprisoned God-fiend called Tharizdun. They foiled the cultist\'s plans and returned the Form Staff to the Gods at the Wounded Peaks as originally intended. \r\n\r\nOver three days and three nights, Yittenor the Ascended labored over his forge to ensure that the Form Staff is preserved in a form that would not harm the world, but to preserve its sentience. It was last seen in the hands of the wholly unpredictable cleric Cloud who was said to have murdered his entire church with a Holy Word during a disagreement.\r\n', 1840537, 1840537),
(3, 'The Pact of the Firstborn', 'The party now known as the Severed Heads traversed Northern Carlohn, putting in check the activities of the Cult of the Dragon, discovering a plot to summon the evil dragon Goddess Tiamat into the world. They discovered the true reason Tiamat manipulated events to return to the world was to forcibly break the Pact of the Firstborn. The pact was made between the dragon Gods and the Makers when it was decided that the sheer power of the dragons was keeping the lesser races from developing to their full potential. To prevent all dragons from being destroyed, the noble silver dragon Varnfang beseeched the Gods for exile instead, and offered himself and his silver dragon kin as enforcers for the Gods. The Makers agreed, exiling the dragons to the unexplored mountains of Dragon\'s Teeth, where the majority of dragons in the world remain confined to this day.\r\n\r\nAfter preventing the summoning of Tiamat, and defeating Ashardalon, a demonic red dragon she sent as an agent to the heart of the mou', 1843201, 1843201),
(4, 'Curse of the Coil', 'A mist swallows the city of Carnarvon in the Seven Kingdoms. General Himo, leading a contingent from the city of Bastion arrives to investigate. Yet all who are sent in do not return, except for an enigmatic priest who claims that only the \"Blood of the First Moon\" may enter. A party who fits these mysterious requirements are assembled, and they enter to discover a world ruled over by the vampire Strahd Carnarvon, his subjects oblivious to the world of Dekarra.\r\n\r\nWith General Himo\'s growing camp as their base of operations, the party of 9 adventurers made numerous forays into the mists. An army from the city of Kelso led by General Hansen Handel marched north and before a way to free the city of Carnarvon could be found, war broke out between the two forces.\r\n\r\nThe truth was eventually discovered that Skall had found a demiplane originally torn from Dekarra floating in the multiverse, and anchored it back where it originally belonged. This manifested as mists covering the city of Carn', 1846801, 1846801);

-- --------------------------------------------------------

--
-- Table structure for table `dekkara_month`
--

CREATE TABLE `dekkara_month` (
  `month_num` tinyint(4) NOT NULL,
  `month_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dekkara_month`
--

INSERT INTO `dekkara_month` (`month_num`, `month_name`) VALUES
(1, 'Cind'),
(2, 'Zara'),
(3, 'Vera'),
(4, 'Ayree'),
(5, 'Nyles'),
(6, 'Nale'),
(7, 'Xid'),
(8, 'Sostan'),
(9, 'Highton'),
(10, 'Erin');

-- --------------------------------------------------------

--
-- Table structure for table `dndclass`
--

CREATE TABLE `dndclass` (
  `ClassID` tinyint(1) UNSIGNED NOT NULL,
  `ClassName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dndclass`
--

INSERT INTO `dndclass` (`ClassID`, `ClassName`) VALUES
(1, 'Artificer'),
(2, 'Barbarian'),
(3, 'Bard'),
(4, 'Cleric'),
(5, 'Druid'),
(6, 'Fighter'),
(7, 'Monk'),
(8, 'Paladin'),
(9, 'Ranger'),
(10, 'Rogue'),
(11, 'Sorcerer'),
(12, 'Warlock'),
(13, 'Wizard');

-- --------------------------------------------------------

--
-- Table structure for table `dndclasssub`
--

CREATE TABLE `dndclasssub` (
  `SubClassID` tinyint(3) UNSIGNED NOT NULL,
  `ClassID` tinyint(1) UNSIGNED NOT NULL,
  `SubClassName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dndclasssub`
--

INSERT INTO `dndclasssub` (`SubClassID`, `ClassID`, `SubClassName`) VALUES
(1, 1, 'Alchemist'),
(2, 1, 'Armorer'),
(3, 1, 'Artillerist'),
(4, 1, 'Battle Smith'),
(5, 1, 'Cartographer'),
(6, 2, 'Berserker'),
(7, 2, 'Wild Heart'),
(8, 2, 'World Tree'),
(9, 2, 'Zealot'),
(10, 2, 'Ancestral Guardian'),
(11, 2, 'Storm Herald'),
(12, 2, 'Battlerager'),
(13, 2, 'Path of the Beast'),
(14, 2, 'Path of Wild Magic'),
(15, 2, 'Path of the Giant'),
(16, 3, 'College of Dance'),
(17, 3, 'College of Glamour'),
(18, 3, 'College of Lore'),
(19, 3, 'College of Valor'),
(20, 3, 'College of Swords'),
(21, 3, 'College of Whispers'),
(22, 3, 'College of Creation'),
(23, 3, 'College of Eloquence'),
(24, 3, 'College of Spirits'),
(25, 4, 'Life Domain'),
(26, 4, 'Light Domain'),
(27, 4, 'Trickery Domain'),
(28, 4, 'War Domain'),
(29, 4, 'Arcana Domain'),
(30, 4, 'Forge Domain'),
(31, 4, 'Grave Domain'),
(32, 4, 'Order Domain'),
(33, 4, 'Peace Domain'),
(34, 4, 'Twilight Domain'),
(35, 5, 'Circle of the Land'),
(36, 5, 'Circle of the Moon'),
(37, 5, 'Circle of the Sea'),
(38, 5, 'Circle of Stars'),
(39, 5, 'Circle of Dreams'),
(40, 5, 'Circle of the Shepherd'),
(41, 5, 'Circle of Wildfire'),
(42, 6, 'Battle Master'),
(43, 6, 'Champion'),
(44, 6, 'Eldritch Knight'),
(45, 6, 'Psi Warrior'),
(46, 6, 'Arcane Archer'),
(47, 6, 'Cavalier'),
(48, 6, 'Samurai'),
(49, 6, 'Rune Knight'),
(50, 6, 'Banneret'),
(51, 6, 'Echo Knight'),
(52, 7, 'Warrior of the Open Hand'),
(53, 7, 'Warrior of Shadow'),
(54, 7, 'Warrior of the Elements'),
(55, 7, 'Warrior of Mercy'),
(56, 7, 'Way of the Drunken Master'),
(57, 7, 'Way of the Kensei'),
(58, 7, 'Way of the Sun Soul'),
(59, 7, 'Way of the Astral Self'),
(60, 7, 'Way of the Ascendant Dragon'),
(61, 7, 'Way of the Long Death'),
(62, 8, 'Oath of Devotion'),
(63, 8, 'Oath of the Ancients'),
(64, 8, 'Oath of Vengeance'),
(65, 8, 'Oath of Glory'),
(66, 8, 'Oath of Conquest'),
(67, 8, 'Oath of Redemption'),
(68, 8, 'Oath of the Crown'),
(69, 8, 'Oath of the Watchers'),
(70, 9, 'Beast Master'),
(71, 9, 'Fey Wanderer'),
(72, 9, 'Gloom Stalker'),
(73, 9, 'Hunter'),
(74, 9, 'Horizon Walker'),
(75, 9, 'Monster Slayer'),
(76, 9, 'Swarmkeeper'),
(77, 9, 'Drakewarden'),
(78, 10, 'Arcane Trickster'),
(79, 10, 'Assassin'),
(80, 10, 'Soulknife'),
(81, 10, 'Thief'),
(82, 10, 'Inquisitive'),
(83, 10, 'Mastermind'),
(84, 10, 'Scout'),
(85, 10, 'Swashbuckler'),
(86, 10, 'Phantom'),
(87, 11, 'Aberrant Mind'),
(88, 11, 'Clockwork Soul'),
(89, 11, 'Draconic Sorcery'),
(90, 11, 'Wild Magic'),
(91, 11, 'Divine Soul'),
(92, 11, 'Shadow Magic'),
(93, 11, 'Storm Sorcery'),
(94, 12, 'Archfey'),
(95, 12, 'Celestial'),
(96, 12, 'Fiend'),
(97, 12, 'Great Old One'),
(98, 12, 'Fathomless'),
(99, 12, 'Genie'),
(100, 12, 'Hexblade'),
(101, 12, 'Undead'),
(102, 12, 'Undying'),
(103, 13, 'Abjurer'),
(104, 13, 'Diviner'),
(105, 13, 'Evoker'),
(106, 13, 'Illusionist'),
(107, 13, 'Bladesinging'),
(108, 13, 'Order of Scribes'),
(109, 13, 'War Magic');

-- --------------------------------------------------------

--
-- Table structure for table `dndspecies`
--

CREATE TABLE `dndspecies` (
  `SpeciesID` tinyint(3) UNSIGNED NOT NULL,
  `SpeciesName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dndspecies`
--

INSERT INTO `dndspecies` (`SpeciesID`, `SpeciesName`) VALUES
(1, 'Custom Lineage'),
(2, 'Aasimar'),
(3, 'Aarakocra'),
(4, 'Bugbear'),
(5, 'Changeling'),
(6, 'Centaur'),
(7, 'Dhampir'),
(8, 'Dragonborn'),
(9, 'Dwarf'),
(10, 'Fairy'),
(11, 'Firbolg'),
(12, 'Genasi'),
(13, 'Githyanki'),
(14, 'Githzerai'),
(15, 'Gnome'),
(16, 'Goblin'),
(17, 'Goliath'),
(18, 'Halfling'),
(19, 'Harengon'),
(20, 'Hobgoblin'),
(21, 'Human'),
(22, 'Kalashtar'),
(23, 'Kenku'),
(24, 'Kobold'),
(25, 'Leonin'),
(26, 'Lizardfolk'),
(27, 'Minotaur'),
(28, 'Orc'),
(29, 'Owlin'),
(30, 'Reborn'),
(31, 'Satyr'),
(32, 'Shifter'),
(33, 'Tabaxi'),
(34, 'Tiefling'),
(35, 'Tortle'),
(36, 'Triton'),
(37, 'Verdan'),
(38, 'Warforged'),
(39, 'Yuan-ti'),
(40, 'Hexblood');

-- --------------------------------------------------------

--
-- Table structure for table `dndspeciessub`
--

CREATE TABLE `dndspeciessub` (
  `SubSpeciesID` tinyint(3) UNSIGNED NOT NULL,
  `SpeciesID` tinyint(3) UNSIGNED NOT NULL,
  `SubSpeciesName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dndspeciessub`
--

INSERT INTO `dndspeciessub` (`SubSpeciesID`, `SpeciesID`, `SubSpeciesName`) VALUES
(1, 2, 'Fallen Aasimar'),
(2, 2, 'Scourge Aasimar'),
(3, 2, 'Protector Aasimar'),
(4, 8, 'Topaz Dragonborn'),
(5, 8, 'Sapphire Dragonborn'),
(6, 8, 'Emerald Dragonborn'),
(7, 8, 'Crystal Dragonborn'),
(8, 8, 'Amethyst Dragonborn'),
(9, 8, 'Gem Dragonborn'),
(10, 8, 'Silver Dragonborn'),
(11, 8, 'Gold Dragonborn'),
(12, 8, 'Copper Dragonborn'),
(13, 8, 'Bronze Dragonborn'),
(14, 8, 'Brass Dragonborn'),
(15, 8, 'Metallic Dragonborn'),
(16, 8, 'White Dragonborn'),
(17, 8, 'Red Dragonborn'),
(18, 8, 'Green Dragonborn'),
(19, 8, 'Blue Dragonborn'),
(20, 8, 'Black Dragonborn'),
(21, 8, 'Chromatic Dragonborn'),
(22, 9, 'Mark of Warding'),
(23, 9, 'Duergar'),
(24, 12, 'Water Genasi'),
(25, 12, 'Fire Genasi'),
(26, 12, 'Earth Genasi'),
(27, 12, 'Air Genasi'),
(28, 15, 'Mark of Scribing'),
(29, 15, 'Deep Gnome'),
(30, 17, 'Storm Giant Goliath'),
(31, 17, 'Stone Giant Goliath'),
(32, 17, 'Hill Giant Goliath'),
(33, 17, 'Frost Giant Goliath'),
(34, 17, 'Fire Giant Goliath'),
(35, 17, 'Cloud Giant Goliath'),
(36, 18, 'Mark of Hospitality'),
(37, 18, 'Mark of Healing'),
(38, 21, 'Mark of Sentinel'),
(39, 21, 'Mark of Passage'),
(40, 21, 'Mark of Making'),
(41, 21, 'Mark of Handling'),
(42, 21, 'Mark of Finding'),
(43, 34, 'Zariel Tiefling'),
(44, 34, 'Mephistopheles Tiefling'),
(45, 34, 'Mammon Tiefling'),
(46, 34, 'Levistus Tiefling'),
(47, 34, 'Glasya Tiefling'),
(48, 34, 'Fierna Tiefling'),
(49, 34, 'Dispater Tiefling'),
(50, 34, 'Baalzebul Tiefling'),
(51, 34, 'Winged Tiefling'),
(52, 34, 'Hellfire Tiefling'),
(53, 34, 'Devil\'s Tongue Tiefling'),
(54, 34, 'Feral Tiefling'),
(55, 34, 'Infernal Tiefling'),
(56, 34, 'Chthonic Tiefling'),
(57, 34, 'Abyssal Tiefling');

-- --------------------------------------------------------

--
-- Table structure for table `environmenttypes`
--

CREATE TABLE `environmenttypes` (
  `EnvironmentID` tinyint(3) UNSIGNED NOT NULL,
  `EnvironmentName` varchar(50) NOT NULL,
  `Description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `environmenttypes`
--

INSERT INTO `environmenttypes` (`EnvironmentID`, `EnvironmentName`, `Description`) VALUES
(1, 'Any', 'Matches any habitat or environment type.'),
(2, 'Arctic', 'Freezing, ice-covered landscapes and tundra regions.'),
(3, 'Badlands', 'Dry, heavily eroded terrain with minimal vegetation.'),
(4, 'Coastal', 'Shorelines, beaches, and areas bordering large bodies of water.'),
(5, 'Desert', 'Arid, hot, or cold regions with extremely low rainfall.'),
(6, 'Farmland', 'Cultivated land, pastures, and rural agricultural areas.'),
(7, 'Forest', 'Woodlands dominated by trees and dense canopy vegetation.'),
(8, 'Grassland', 'Open plains, savannas, and prairies dominated by grasses.'),
(9, 'Hill', 'Undulating terrain rising above the surrounding landscape.'),
(10, 'Mountain', 'High-altitude, steep rocky peaks and alpine zones.'),
(11, 'Swamp', 'Low-lying, waterlogged wetlands, marshes, and bogs.'),
(12, 'Underdark', 'Vast, subterranean world filled with bizarre ecosystems.'),
(13, 'Underground', 'Subterranean caves, tunnels, and deep cavern systems.'),
(14, 'Underwater', 'Aquatic environments including oceans, lakes, and rivers.'),
(15, 'Urban', 'Cities, towns, settlements, and heavily built-up areas.'),
(16, 'Planar (Abyss)', 'An infinite plane of absolute chaos and pure, unmitigated evil.'),
(17, 'Planar (Acheron)', 'A plane of eternal battlefields and massive drifting iron cubes.'),
(18, 'Planar (Elemental Plane of Air)', 'An endless expanse of open sky, clouds, and flying islands.'),
(19, 'Planar (Arborea)', 'A plane of vibrant, wild nature, immense beauty, and passions.'),
(20, 'Planar (Arcadia)', 'A realm of perfect order, harmony, and pristine orchards.'),
(21, 'Planar (Para-elemental Plane of Ash)', 'The choking, burning border region between Fire and Negative Energy.'),
(22, 'Planar (Astral Plane)', 'The silvery void linking the Prime Material to the Outer Planes.'),
(23, 'Planar (Beastlands)', 'A boundless wilderness populated by untamed nature and animals.'),
(24, 'Planar (Bytopia)', 'A dual-layered plane of honest toil, craft, and pastoral peace.'),
(25, 'Planar (Carceri)', 'A multi-layered prison plane of endless bogs, deserts, and despair.'),
(26, 'Planar (Elemental Plane of Earth)', 'An infinite expanse of solid rock, gems, and claustrophobic tunnels.'),
(27, 'Planar (Elemental Chaos)', 'A swirling, volatile mixture of raw elements constantly shifting.'),
(28, 'Planar (Elysium)', 'A plane of absolute goodness, peaceful rest, and tranquility.'),
(29, 'Planar (Ethereal Plane)', 'A misty, ghostly dimension parallel to the Material Plane.'),
(30, 'Planar (Feywild)', 'A vibrant, magical echo of the world filled with wild emotions.'),
(31, 'Planar (Elemental Plane of Fire)', 'A blistering ocean of flame, smoke, and rivers of liquid magma.'),
(32, 'Planar (Gehenna)', 'Four steep, volcanic mountainsides of cruel greed and suspicion.'),
(33, 'Planar (Hades)', 'The bleak, grey underworld of pure apathy and decaying spirits.'),
(34, 'Planar (Para-elemental Plane of Ice)', 'An infinite glacier formed where Air meets Water.'),
(35, 'Planar (Limbo)', 'A soup of pure, unshaped chaos controlled only by willpower.'),
(36, 'Planar (Para-elemental Plane of Magma)', 'A shifting sea of molten rock where Earth meets Fire.'),
(37, 'Planar (Mechanus)', 'A plane of absolute law governed by infinitely interlocking clockwork gears.'),
(38, 'Planar (Mount Celestia)', 'Seven tiers of holy light, divine justice, and ultimate goodness.'),
(39, 'Planar (Nine Hells)', 'Nine strictly structured tiers of tyrannical laws and infernal evil.'),
(40, 'Planar (Para-elemental Plane of Ooze)', 'A suffocating, stagnant mire where Earth meets Water.'),
(41, 'Planar (Outlands)', 'The neutral crossroads of the Outer Planes centered around the Spire.'),
(42, 'Planar (Pandemonium)', 'An endless labyrinth of dark, howling subterranean wind tunnels.'),
(43, 'Planar (Shadowfell)', 'A dark, melancholy, and decayed reflection of the Material Plane.'),
(44, 'Planar (Valhalla)', 'The heroic eternal battlefield and great hall of fallen warriors.'),
(45, 'Planar (Elemental Plane of Water)', 'An endless, sunlit ocean stretching out in all directions.'),
(46, 'Planar (World Tree)', 'A massive cosmic structure connecting multiple pantheons and realms.'),
(47, 'Planar (Ysgard)', 'A realm of floating earthmotes, heroic duels, and wild storms.');

-- --------------------------------------------------------

--
-- Table structure for table `faction`
--

CREATE TABLE `faction` (
  `FactionID` int(10) UNSIGNED NOT NULL,
  `FactionName` varchar(100) NOT NULL,
  `Description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faction`
--

INSERT INTO `faction` (`FactionID`, `FactionName`, `Description`) VALUES
(1, 'The Princes\' Alliance', 'Founded by idealistic heirs of several kingdoms some centuries ago in order to prevent all out war, the Princes\' Alliance is one of the oldest organisations in Dekarra. It has been instrumental in preventing many wars by connecting the younger members of the nobility across the nations in Dekarra. Its members are not only made up of the younger generation, and it is no longer controlled by nobles alone.\r\n\r\nNote: Replaces The Lord\'s Alliance from the Forgotten Realms\r\n'),
(2, 'Night Hoods', 'The Night Hoods are known as information brokers, and are known to work with the cities and towns they are a part of, and as such they are often welcome and even consulted in their towns and cities. It is an open secret however that there is a hidden half of the Night Hoods that operate outside the law. Many cities have thieves guilds, and some of these guilds are part of the Night Hoods network. They\'re loosely organised and rumors abound that the central influence of the organisation comes out of the city of Uralt in Northern Carlohn.\r\n\r\nNote: Replaces The Harpers from the Forgotten Realms\r\n'),
(3, 'The Green Watchers', 'Note: Replaces The Emerald Enclave from the Forgotten Realms'),
(4, 'Darkblades', 'Note: Replaces the Zhentarim from the Forgotten Realms'),
(5, 'The Adjudicators', 'Note: Replaces The Order of the Gauntlet'),
(6, 'Hand of Zaradin', 'Note: Replaces The Order of the Gauntlet ');

-- --------------------------------------------------------

--
-- Table structure for table `faction_npc`
--

CREATE TABLE `faction_npc` (
  `FactionNPCID` int(10) UNSIGNED NOT NULL,
  `FactionID` int(10) UNSIGNED NOT NULL,
  `NPCID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faction_pc`
--

CREATE TABLE `faction_pc` (
  `FactionPCID` int(10) UNSIGNED NOT NULL,
  `FactionID` int(10) UNSIGNED NOT NULL,
  `PCID` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `InventoryID` mediumint(8) UNSIGNED NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `PCID` mediumint(8) UNSIGNED DEFAULT NULL,
  `InventoryName` varchar(50) NOT NULL DEFAULT 'Backpack'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_coin`
--

CREATE TABLE `inventory_coin` (
  `InventoryCoinID` mediumint(8) UNSIGNED NOT NULL,
  `InventoryID` mediumint(8) UNSIGNED NOT NULL,
  `PP` int(10) UNSIGNED NOT NULL,
  `GP` int(10) UNSIGNED NOT NULL,
  `EP` int(10) UNSIGNED NOT NULL,
  `SP` int(10) UNSIGNED NOT NULL,
  `CP` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_item`
--

CREATE TABLE `inventory_item` (
  `InventoryItemID` mediumint(8) UNSIGNED NOT NULL,
  `InventoryID` mediumint(8) UNSIGNED NOT NULL,
  `ItemID` mediumint(8) UNSIGNED NOT NULL,
  `Quantity` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_building`
--

CREATE TABLE `location_building` (
  `BuildingID` int(10) UNSIGNED NOT NULL,
  `BuildingName` varchar(100) NOT NULL,
  `SettlementID` int(10) UNSIGNED NOT NULL,
  `Notes` text DEFAULT NULL,
  `MapX` decimal(5,2) DEFAULT NULL,
  `MapY` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_buildingtype`
--

CREATE TABLE `location_buildingtype` (
  `TypeID` tinyint(3) UNSIGNED NOT NULL,
  `BuildingID` int(10) UNSIGNED NOT NULL,
  `TypeName` varchar(50) NOT NULL,
  `TypeDesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_region`
--

CREATE TABLE `location_region` (
  `RegionID` int(10) UNSIGNED NOT NULL,
  `RegionName` varchar(100) NOT NULL,
  `Notes` text DEFAULT NULL,
  `MapX` decimal(5,2) DEFAULT NULL,
  `MapY` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location_region`
--

INSERT INTO `location_region` (`RegionID`, `RegionName`, `Notes`, `MapX`, `MapY`) VALUES
(2, 'Spirecrest', 'Guild Location', 22.34, 34.67);

-- --------------------------------------------------------

--
-- Table structure for table `location_regiontype`
--

CREATE TABLE `location_regiontype` (
  `TypeID` tinyint(3) UNSIGNED NOT NULL,
  `RegionID` int(10) UNSIGNED NOT NULL,
  `TypeName` varchar(50) NOT NULL,
  `TypeDesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_settlement`
--

CREATE TABLE `location_settlement` (
  `SettlementID` int(10) UNSIGNED NOT NULL,
  `SettlementName` varchar(100) NOT NULL,
  `RegionID` int(10) UNSIGNED NOT NULL,
  `Notes` text DEFAULT NULL,
  `MapX` decimal(5,2) DEFAULT NULL,
  `MapY` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_settlementtype`
--

CREATE TABLE `location_settlementtype` (
  `TypeID` tinyint(3) UNSIGNED NOT NULL,
  `SettlementID` int(10) UNSIGNED NOT NULL,
  `TypeName` varchar(50) NOT NULL,
  `TypeDesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mission`
--

CREATE TABLE `mission` (
  `MissionID` tinyint(3) UNSIGNED NOT NULL,
  `MissionName` varchar(50) NOT NULL,
  `Description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `npc`
--

CREATE TABLE `npc` (
  `NPCID` int(10) UNSIGNED NOT NULL,
  `UserID` smallint(5) UNSIGNED NOT NULL,
  `NPCName` varchar(50) NOT NULL,
  `InventoryID` mediumint(8) UNSIGNED DEFAULT NULL,
  `NPCStatus` tinyint(3) UNSIGNED NOT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `npc`
--

INSERT INTO `npc` (`NPCID`, `UserID`, `NPCName`, `InventoryID`, `NPCStatus`, `UpdatedAt`) VALUES
(1, 0, 'Zaradin', NULL, 9, '2026-09-14 05:15:24'),
(2, 0, 'Veraine', NULL, 9, '2026-09-14 05:15:24'),
(3, 0, 'Pahpeebee', NULL, 9, '2026-09-14 05:15:24'),
(4, 0, 'K’nyle', NULL, 9, '2026-09-14 05:15:24'),
(5, 0, 'Naele', NULL, 9, '2026-09-14 05:15:24'),
(6, 0, 'Xvrydza', NULL, 9, '2026-09-14 05:15:24'),
(7, 0, 'Soxtis', NULL, 9, '2026-09-14 05:15:24'),
(8, 0, 'Hit’oe Kiri', NULL, 9, '2026-09-14 05:15:24'),
(9, 0, 'Ehryna', NULL, 9, '2026-09-14 05:15:24'),
(10, 0, 'Klawon', NULL, 9, '2026-09-14 05:15:24'),
(11, 0, 'Aleuse', NULL, 9, '2026-09-14 05:15:24'),
(12, 0, 'Ornald', NULL, 9, '2026-09-14 05:15:24'),
(13, 0, 'Varnfang', NULL, 9, '2026-09-14 05:15:31'),
(14, 0, 'Miastar', NULL, 9, '2026-09-14 05:15:24'),
(15, 0, 'Yittenor', NULL, 9, '2026-09-14 05:15:24'),
(16, 0, 'Driathax', NULL, 9, '2026-09-14 05:15:24'),
(17, 0, 'Grodin', NULL, 9, '2026-09-14 05:15:24'),
(18, 0, 'Skall', NULL, 9, '2026-09-14 05:15:24'),
(19, 0, 'Kyndar ', NULL, 9, '2026-09-14 05:15:24'),
(20, 0, 'Israfil', NULL, 9, '2026-09-14 05:15:24');

-- --------------------------------------------------------

--
-- Table structure for table `npc_bio`
--

CREATE TABLE `npc_bio` (
  `BioID` int(10) UNSIGNED NOT NULL,
  `NPCID` int(10) UNSIGNED NOT NULL,
  `SpeciesID` tinyint(3) UNSIGNED NOT NULL,
  `SubSpeciesID` tinyint(3) UNSIGNED DEFAULT NULL,
  `Age` smallint(5) NOT NULL,
  `Alignment` enum('Lawful Good','Neutral Good','Chaotic Good','Lawful Neutral','True Neutral','Chaotic Neutral','Lawful Evil','Neutral Evil','Chaotic Evil') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `npc_class`
--

CREATE TABLE `npc_class` (
  `ClassID` int(10) UNSIGNED NOT NULL,
  `NPCID` int(10) UNSIGNED NOT NULL,
  `Class1` tinyint(1) UNSIGNED DEFAULT NULL,
  `SubClass1` tinyint(1) UNSIGNED DEFAULT NULL,
  `Level1` tinyint(1) UNSIGNED DEFAULT NULL,
  `Class2` tinyint(1) UNSIGNED DEFAULT NULL,
  `SubClass2` tinyint(1) UNSIGNED DEFAULT NULL,
  `Level2` tinyint(1) UNSIGNED DEFAULT NULL,
  `Class3` tinyint(1) UNSIGNED DEFAULT NULL,
  `SubClass3` tinyint(1) UNSIGNED DEFAULT NULL,
  `Level3` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `npc_info`
--

CREATE TABLE `npc_info` (
  `NPCInfoID` int(10) UNSIGNED NOT NULL,
  `NPCID` int(10) UNSIGNED NOT NULL,
  `Info` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `npc_stat`
--

CREATE TABLE `npc_stat` (
  `NPCStatID` mediumint(8) UNSIGNED NOT NULL,
  `NPCID` int(10) UNSIGNED NOT NULL,
  `STR` int(11) NOT NULL,
  `DEX` int(11) NOT NULL,
  `CON` int(11) NOT NULL,
  `INT` int(11) NOT NULL,
  `WIS` int(11) NOT NULL,
  `CHA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `party`
--

CREATE TABLE `party` (
  `PartyID` int(10) UNSIGNED NOT NULL,
  `SessionID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `party_member`
--

CREATE TABLE `party_member` (
  `PartyID` int(10) UNSIGNED NOT NULL,
  `PCID` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pc`
--

CREATE TABLE `pc` (
  `PCID` mediumint(8) UNSIGNED NOT NULL,
  `UserID` smallint(5) UNSIGNED NOT NULL,
  `PCName` varchar(50) NOT NULL,
  `InventoryID` mediumint(8) UNSIGNED DEFAULT NULL,
  `PCStatus` tinyint(3) UNSIGNED NOT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pc_bio`
--

CREATE TABLE `pc_bio` (
  `BioID` int(10) UNSIGNED NOT NULL,
  `PCID` mediumint(8) UNSIGNED NOT NULL,
  `SpeciesID` tinyint(3) UNSIGNED NOT NULL,
  `SubSpeciesID` tinyint(3) UNSIGNED DEFAULT NULL,
  `Age` smallint(5) NOT NULL,
  `Alignment` enum('Lawful Good','Neutral Good','Chaotic Good','Lawful Neutral','True Neutral','Chaotic Neutral','Lawful Evil','Neutral Evil','Chaotic Evil') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pc_class`
--

CREATE TABLE `pc_class` (
  `ClassID` mediumint(8) UNSIGNED NOT NULL,
  `PCID` mediumint(8) UNSIGNED NOT NULL,
  `Class1` tinyint(1) UNSIGNED DEFAULT NULL,
  `SubClass1` tinyint(1) UNSIGNED DEFAULT NULL,
  `Level1` tinyint(1) UNSIGNED DEFAULT NULL,
  `Class2` tinyint(1) UNSIGNED DEFAULT NULL,
  `SubClass2` tinyint(1) UNSIGNED DEFAULT NULL,
  `Level2` tinyint(1) UNSIGNED DEFAULT NULL,
  `Class3` tinyint(1) UNSIGNED DEFAULT NULL,
  `SubClass3` tinyint(1) UNSIGNED DEFAULT NULL,
  `Level3` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pc_stat`
--

CREATE TABLE `pc_stat` (
  `PCStatID` mediumint(8) UNSIGNED NOT NULL,
  `PCID` mediumint(8) UNSIGNED NOT NULL,
  `STR` int(11) NOT NULL,
  `DEX` int(11) NOT NULL,
  `CON` int(11) NOT NULL,
  `INT` int(11) NOT NULL,
  `WIS` int(11) NOT NULL,
  `CHA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pc_stat`
--

INSERT INTO `pc_stat` (`PCStatID`, `PCID`, `STR`, `DEX`, `CON`, `INT`, `WIS`, `CHA`) VALUES
(1, 2, 10, 10, 10, 16, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `quest`
--

CREATE TABLE `quest` (
  `QuestID` int(10) UNSIGNED NOT NULL,
  `QuestName` varchar(100) NOT NULL,
  `QuestTier` tinyint(1) UNSIGNED NOT NULL,
  `QuestLevel` tinyint(1) UNSIGNED NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quest`
--

INSERT INTO `quest` (`QuestID`, `QuestName`, `QuestTier`, `QuestLevel`, `Description`) VALUES
(1, 'Hocus Locust', 2, 10, 'The piece of parchment has uneven edges. It looks to have been chewed on by something.\r\n\r\nDear Adventurers of DICE,\r\n\r\nWe are at odds with our neighbours, the Fairies of Jenx.\r\n\r\nDue to budget cuts, our local talent messed up on removing the Remorhaz terrorizing us. He has turned alot of fairies into bugs. \r\n\r\nWhom in turn turned us into bugs. \r\n\r\nPlease mediate and help us.\r\n\r\nVillage Head of Hix\r\n\r\nThe writing gets increasingly tinier at the end.\r\nOh no \r\n\r\nTinier\r\nam bug');

-- --------------------------------------------------------

--
-- Table structure for table `quest_building`
--

CREATE TABLE `quest_building` (
  `QuestLocationID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `BuildingID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_environment`
--

CREATE TABLE `quest_environment` (
  `QuestEnvironmentID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `EnviromentID` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_mission`
--

CREATE TABLE `quest_mission` (
  `QuestMissionID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `MissionID` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_npc`
--

CREATE TABLE `quest_npc` (
  `QuestNPCID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `NPCID` int(10) UNSIGNED NOT NULL,
  `Involvement` enum('Present','Mentioned','','') NOT NULL,
  `Description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_region`
--

CREATE TABLE `quest_region` (
  `QuestLocationID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `RegionID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_rewards`
--

CREATE TABLE `quest_rewards` (
  `RewardID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `RewardType` tinyint(2) UNSIGNED NOT NULL,
  `AmountOrValue` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_settlement`
--

CREATE TABLE `quest_settlement` (
  `QuestLocationID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `SettlementID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_theme`
--

CREATE TABLE `quest_theme` (
  `QuestThemeID` int(10) UNSIGNED NOT NULL,
  `ThemeID` smallint(5) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quest_warning`
--

CREATE TABLE `quest_warning` (
  `QuestWarningID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `WarningID` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `relationship`
--

CREATE TABLE `relationship` (
  `RelationshipID` tinyint(3) UNSIGNED NOT NULL,
  `SubjectType` enum('PC','NPC','Faction') NOT NULL,
  `SubjectID` mediumint(8) UNSIGNED NOT NULL,
  `TargetType` enum('PC','NPC','Faction') NOT NULL,
  `TargetID` mediumint(8) UNSIGNED NOT NULL,
  `BubbleID` tinyint(1) UNSIGNED NOT NULL,
  `Note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relationship`
--

INSERT INTO `relationship` (`RelationshipID`, `SubjectType`, `SubjectID`, `TargetType`, `TargetID`, `BubbleID`, `Note`) VALUES
(1, 'PC', 2, 'PC', 3, 27, 'Uncle :)'),
(2, 'PC', 2, 'NPC', 3, 27, 'Aunt :)');

-- --------------------------------------------------------

--
-- Table structure for table `relationship_bubble`
--

CREATE TABLE `relationship_bubble` (
  `BubbleID` tinyint(3) UNSIGNED NOT NULL,
  `BubbleCategory` enum('Passive','Positive','Negative','Romantic','Conflictive') NOT NULL,
  `BubbleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relationship_bubble`
--

INSERT INTO `relationship_bubble` (`BubbleID`, `BubbleCategory`, `BubbleName`) VALUES
(1, 'Passive', 'Haven\'t Met'),
(2, 'Passive', '? Curious ?'),
(3, 'Passive', '? Confused ?'),
(4, 'Passive', 'Don\'t care'),
(5, 'Passive', 'Chill'),
(6, 'Passive', 'Mixed feelings'),
(7, 'Passive', 'Suspicious'),
(8, 'Passive', 'Cautious'),
(9, 'Passive', 'Neutral'),
(10, 'Passive', 'Jealous'),
(11, 'Passive', 'Awkward'),
(12, 'Passive', 'Anxious'),
(13, 'Passive', '! Amused !'),
(14, 'Passive', 'Apathy'),
(15, 'Passive', 'Rivals'),
(16, 'Positive', 'Like'),
(17, 'Positive', 'Friendly'),
(18, 'Positive', 'Fond'),
(19, 'Positive', 'Friends'),
(20, 'Positive', 'Close friends'),
(21, 'Positive', 'Best friend'),
(22, 'Positive', 'Trust'),
(23, 'Positive', 'Acquaintance'),
(24, 'Positive', 'Respect'),
(25, 'Positive', 'Empathy'),
(26, 'Positive', 'Admire'),
(27, 'Positive', 'Family'),
(28, 'Positive', 'Determination'),
(29, 'Negative', 'Dislike'),
(30, 'Negative', 'Annoyed'),
(31, 'Negative', 'Ignore'),
(32, 'Negative', 'HATE'),
(33, 'Negative', 'DETEST'),
(34, 'Negative', 'ENRAGED'),
(35, 'Negative', 'Bitter'),
(36, 'Negative', 'Distrust'),
(37, 'Negative', 'Disgust'),
(38, 'Negative', 'Fear'),
(39, 'Negative', 'Horrified'),
(40, 'Negative', 'Uncomfortable'),
(41, 'Negative', 'Anxiety'),
(42, 'Romantic', 'Crush ♡'),
(43, 'Romantic', 'Lovers ♡♡'),
(44, 'Romantic', 'Simp ♡♡♡'),
(45, 'Romantic', '♡♡ Obsessed ♡♡'),
(46, 'Romantic', 'Platonic Love'),
(47, 'Romantic', '♡♡♡ Lovestruck ♡♡♡'),
(48, 'Romantic', '♡ Couple ♡'),
(49, 'Conflictive', 'Insulted'),
(50, 'Conflictive', 'Hurt'),
(51, 'Conflictive', 'Vengeful'),
(52, 'Conflictive', 'Heartbroken'),
(53, 'Conflictive', 'Pity'),
(54, 'Conflictive', 'Guilty'),
(55, 'Conflictive', 'Concern'),
(56, 'Conflictive', 'Doesn\'t exist'),
(57, 'Conflictive', 'Traumatized'),
(58, 'Conflictive', 'Mourn');

-- --------------------------------------------------------

--
-- Table structure for table `rewardtypes`
--

CREATE TABLE `rewardtypes` (
  `TypeID` tinyint(2) UNSIGNED NOT NULL,
  `TypeName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `SessionID` int(10) UNSIGNED NOT NULL,
  `UserID` smallint(5) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `AttemptDate` date NOT NULL,
  `Status` enum('Active','Completed','Failed') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_feedback_dm`
--

CREATE TABLE `session_feedback_dm` (
  `FeedbackID` int(10) UNSIGNED NOT NULL,
  `SessionID` int(10) UNSIGNED NOT NULL,
  `DMID` smallint(5) UNSIGNED NOT NULL,
  `Attentiveness` tinyint(1) UNSIGNED NOT NULL,
  `Fairness` tinyint(1) UNSIGNED NOT NULL,
  `Immersion` tinyint(1) UNSIGNED NOT NULL,
  `Engagement` tinyint(1) UNSIGNED NOT NULL,
  `Adaptability` tinyint(1) UNSIGNED NOT NULL,
  `Compliments` varchar(1000) NOT NULL,
  `Issues` varchar(1000) NOT NULL,
  `Advices` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_feedback_module`
--

CREATE TABLE `session_feedback_module` (
  `FeedbackID` int(10) UNSIGNED NOT NULL,
  `SessionID` int(10) UNSIGNED NOT NULL,
  `QuestID` int(10) UNSIGNED NOT NULL,
  `Theme` tinyint(1) UNSIGNED NOT NULL,
  `Mechanics` tinyint(1) UNSIGNED NOT NULL,
  `Descriptions` tinyint(1) UNSIGNED NOT NULL,
  `Elaborations` tinyint(1) UNSIGNED NOT NULL,
  `NPCs` tinyint(1) UNSIGNED NOT NULL,
  `Compliments` varchar(1000) NOT NULL,
  `Issues` varchar(1000) NOT NULL,
  `Advices` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_feedback_player`
--

CREATE TABLE `session_feedback_player` (
  `FeedbackID` int(10) UNSIGNED NOT NULL,
  `PartyID` int(10) UNSIGNED NOT NULL,
  `Attentiveness` tinyint(1) UNSIGNED NOT NULL,
  `Cooperation` tinyint(1) UNSIGNED NOT NULL,
  `Immersion` tinyint(1) UNSIGNED NOT NULL,
  `Engagement` tinyint(1) UNSIGNED NOT NULL,
  `Decisiveness` tinyint(1) UNSIGNED NOT NULL,
  `Compliments` varchar(1000) NOT NULL,
  `Issues` varchar(1000) NOT NULL,
  `Advice` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_reward`
--

CREATE TABLE `session_reward` (
  `SessionRewardID` int(10) UNSIGNED NOT NULL,
  `SessionID` int(10) UNSIGNED NOT NULL,
  `PCID` mediumint(8) UNSIGNED NOT NULL,
  `RewardTypeID` tinyint(2) UNSIGNED NOT NULL,
  `Value` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `ItemID` mediumint(8) UNSIGNED NOT NULL,
  `ItemName` varchar(50) NOT NULL,
  `ItemPrice` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`ItemID`, `ItemName`, `ItemPrice`) VALUES
(1, 'Ring of Animal Influence', 4050),
(2, 'Star rose quartz', 50),
(3, 'Spell Scroll, Cantrip', 30),
(4, 'Potion of Healing', 50),
(5, 'Potion of Comprehension', 50),
(6, 'Spell Scroll, 1st', 50),
(7, 'Mystery Key', 50),
(8, 'Bead of Nourishment', 75),
(9, 'Bead of Refreshment', 75),
(10, 'Walloping Ammunition', 75),
(11, 'Dark Shard Amulet', 120),
(12, 'Hat of Wizardy', 120),
(13, 'Veteran\'s Cane', 134),
(14, 'Spellwrought Tattoo, Cantrip', 134),
(15, 'Potion of Greater Healing', 150),
(16, 'Instrument of Scribing', 180),
(17, 'Masque Charm', 200),
(18, 'Spell Scroll, 2nd', 200),
(19, 'Perfume of Bewitching', 200),
(20, 'Spellwrought Tattoo, Lv1', 200),
(21, 'Wand of Conducting', 270),
(22, 'Wand of Pyrotechnics', 270),
(23, 'Wand of Scowls', 270),
(24, 'Wand of Smiles', 270),
(25, 'Staff of the Adder', 282),
(26, 'Feather Token', 300),
(27, 'Spell Scroll, 3rd', 300),
(28, 'Potion of Animal Friendship', 300),
(29, 'Potion of Fire Breath', 300),
(30, 'Potion of Growth', 300),
(31, 'Potion of Water Breathing', 300),
(32, 'Silver (Material Price)', 300),
(33, 'Charlatan\'s Die', 300),
(34, 'Instrument of Illusions', 300),
(35, 'Ruby of the War Mage', 300),
(36, 'Arcane Grimoire (Homebrew)', 300),
(37, 'Amulet of the Devout (Homebrew)', 300),
(38, 'Rhythm-Maker\'s Drum (Homebrew)', 300),
(39, 'Dragonhide Belt (Homebrew)', 300),
(40, 'Elixir of Health', 400),
(41, 'Potion of Superior Healing', 400),
(42, 'Alchemist\'s Doom', 400),
(43, 'Potion of Diminution', 400),
(44, 'Potion of Invisibility', 400),
(45, 'Potion of Mind Reading', 400),
(46, 'Quaal\'s Feather Token, Fan', 400),
(47, 'Quaal\'s Feather Token, Whip', 400),
(48, 'Potion of Aqueous Form', 400),
(49, 'Arrow of Slaying', 401),
(50, 'Enspelled Staff (Cantrip)', 413),
(51, 'Armor of Gleaming', 450),
(52, 'Boots of False Tracks', 450),
(53, 'Candle of the Deep', 450),
(54, 'Cast-Off Armor', 450),
(55, 'Cloak of Billowing', 450),
(56, 'Clothes of Mending', 450),
(57, 'Dread Helm', 450),
(58, 'Enduring Spellbook', 450),
(59, 'Heward\'s Handy Spice Pouch', 450),
(60, 'Orb of Direction', 450),
(61, 'Orb of Time', 450),
(62, 'Pipe of Smoke Monsters', 450),
(63, 'Pole of Angling', 450),
(64, 'Rope of Mending', 450),
(65, 'Smoldering Armor', 450),
(66, 'Staff of Adornment', 450),
(67, 'Staff of Birdcalls', 450),
(68, 'Staff of Flowers', 450),
(69, 'Unbreakable Arrow', 450),
(70, 'Common Glamerweave', 450),
(71, 'Strixhaven Pennant', 450),
(72, 'Hat of Vermin', 450),
(73, 'Potion of Pugilism', 450),
(74, 'Eyes of Charming', 450),
(75, 'Quaal\'s Feather Token, Tree', 450),
(76, 'Shield of Expression', 465),
(77, 'Guardian Emblem', 465),
(78, 'All-Purpose Tool (Homebrew)', 480),
(79, 'Illuminator\'s Tattoo', 500),
(80, 'Masquerade Tattoo', 500),
(81, 'Talking Doll', 500),
(82, 'Keycharm', 500),
(83, 'Scribe\'s Pen', 500),
(84, 'Orb of Shielding', 510),
(85, 'Horn of Silent Alarm', 525),
(86, 'Potion of Climbing', 530),
(87, 'Rope of Entanglement', 550),
(88, 'Primer, Lorehold/Prismari/Quandrix/Silverquill/Wit', 570),
(89, 'Potion of Heroism', 667),
(90, 'Quiver of Ehlonna', 675),
(91, 'Rope of Climbing', 675),
(92, 'Wand of Magic Detection', 675),
(93, 'Bell Branch', 680),
(94, 'Enspelled Staff (Level 1)', 683),
(95, 'Ring of the Ram', 708),
(96, 'Cloak of Many Fashions', 750),
(97, 'Ear Horn of Hearing', 750),
(98, 'Ersatz Eye', 750),
(99, 'Lock of Trickery', 750),
(100, 'Tankard of Sobriety', 750),
(101, 'Prosthetic Limb', 750),
(102, 'Cleansing Stone', 750),
(103, 'Shiftweave', 750),
(104, 'Ammunition, +1', 750),
(105, 'Boots of Striding and Springing', 750),
(106, 'Ring of Jumping', 750),
(107, 'Moon Sickle (Homebrew)', 750),
(108, 'Barrier Tattoo, AC12+Dex', 750),
(109, 'Clockwork Amulet', 800),
(110, 'Cuddly Strixhaven Mascot', 800),
(111, 'Mithral (Material Price)', 800),
(112, 'Moon-Touched Sword', 900),
(113, 'Heward\'s Handy Haversack', 900),
(114, 'Quaal\'s Feather Token, Swan Boat', 900),
(115, 'Adamantine (Material Price)', 900),
(116, 'Helm of Telepathy', 935),
(117, 'Uncommon Glamerweave', 945),
(118, 'Uncommon Glamerweave', 945),
(119, 'Potion of Supreme Healing', 964),
(120, 'Keoghtom\'s Ointment', 997),
(121, 'Ioun Stone, Sustenance', 1),
(122, 'Ring of Feather Falling', 1),
(123, 'Earworm', 1),
(124, 'Ghost Lantern (TOA)', 1),
(125, 'Necklace of Prayer Beads, Blessing Bead', 1),
(126, 'Dust of Dryness', 1),
(127, 'Bag of Holding', 1),
(128, 'Helm of Comprehending Languages', 1),
(129, 'Wand of Magic Missiles', 1),
(130, 'Figurine of Wondrous Power, Silver Raven', 1),
(131, 'Sending Stones', 1),
(132, 'Wind Fan', 1),
(133, 'Pipes of the Sewers', 1),
(134, 'Bloodwell Vial (Homebrew)', 1),
(135, 'Rod of the Pact Keeper (Homebrew)', 1),
(136, 'Decanter of Endless Water', 1),
(137, 'Siren Song Lyre', 1),
(138, 'Animated Shield', 1),
(139, 'Pot of Awakening', 1),
(140, 'Oil of Slipperiness', 1),
(141, 'Potion of Resistance', 1),
(142, 'Spellwrought Tattoo, Lv2', 1),
(143, 'Figurine of Wondrous Power, Ebony Fly', 1),
(144, 'Figurine of Wondrous Power, Onyx Dog', 1),
(145, 'Figurine of Wondrous Power, Serpentine Owl', 1),
(146, 'Medallion of Thoughts', 1),
(147, 'Necklace of Prayer Beads, Curing Bead', 1),
(148, 'Necklace of Prayer Beads, Smiting Bead', 1),
(149, 'Wand Sheath', 1),
(150, 'Docent', 1),
(151, 'Wand of Secrets', 1),
(152, 'Alchemy Jug', 1),
(153, 'Cap of Water Breathing', 1),
(154, 'Enspelled Armor (Cantrip)', 1),
(155, 'Enspelled Weapon (Cantrip)', 1),
(156, 'Amulet of Proof Against Detect and Location', 1),
(157, 'Mithral Ring Mail', 1),
(158, 'Javelin of Lightning', 1),
(159, 'Mithral Chain Shirt', 1),
(160, 'Mithral Scale Mail', 1),
(161, 'Mithral Chain Mail', 1),
(162, 'Pole of Collapsing', 2),
(163, 'Everbright Lantern', 2),
(164, 'Spellshard', 2),
(165, 'Ammunition, +2', 2),
(166, 'Gloves of Missile Snaring', 2),
(167, 'Hat of Disguise', 2),
(168, 'Periapt of Wound Closure', 2),
(169, 'Wand of Web', 2),
(170, 'Potion of Clairvoyance', 2),
(171, 'Potion of Gaseous Form', 2),
(172, 'Scroll of Protection', 2),
(173, 'Spell Scroll, 4th', 2),
(174, 'Nature\'s Mantle', 2),
(175, 'Hag Eye', 2),
(176, 'Adamantine Ring Mail', 2),
(177, 'Adamantine Chain Shirt', 2),
(178, 'Adamantine Scale Mail', 2),
(179, 'Adamantine Chain Mail', 2),
(180, 'Mithral Splint', 2),
(181, 'Philter of Love', 2),
(182, 'Spellwrought Tattoo, Lv3', 2),
(183, 'Armblade', 2),
(184, 'Imbued Wood', 2),
(185, 'Imbued Wood (Fernian Ash)', 2),
(186, 'Imbued Wood (Irian Rosewood)', 2),
(187, 'Imbued Wood (Kythrian Machineel)', 2),
(188, 'Imbued Wood (Lamnnian Oak)', 2),
(189, 'Imbued Wood (Mabaran Ebony)', 2),
(190, 'Imbued Wood (Risian Pine)', 2),
(191, 'Imbued Wood (Shavarran Birch)', 2),
(192, 'Imbued Wood (Xorian Wenge)', 2),
(193, 'Survival Mantle', 2),
(194, 'Figurine of Wondrous Power, Ivory Goats', 2),
(195, 'Ghost Step Tattoo', 2),
(196, 'Enspelled Staff (Level 2)', 2),
(197, 'Adamantine Splint', 2),
(198, 'Driftglobe', 2),
(199, 'Wand of Fear', 2),
(200, 'Boots of Levitation', 2),
(201, 'Boots of Speed', 2),
(202, 'Chime of Opening', 2),
(203, 'Ring of X-Ray Vision', 2),
(204, 'Barrier Tattoo, AC15+Dex2', 2),
(205, 'Elemental Essence Shard, Air', 2),
(206, 'Elemental Essence Shard, Earth', 2),
(207, 'Outer Essence Shard, Chaotic', 2),
(208, 'Outer Essence Shard, Good', 2),
(209, 'Mithral Breastplate', 2),
(210, 'Iron Bands of Bilarro', 2),
(211, 'Pipes of Haunting', 2),
(212, 'Enspelled Armor (Level 1)', 2),
(213, 'Enspelled Weapon (Level 1)', 2),
(214, 'Adamantine Breastplate', 2),
(215, 'Goggles of Night', 2),
(216, 'Bottle of Boundless Coffee', 3),
(217, 'Boots of Elvenkind', 3),
(218, 'Circlet of Blasting', 3),
(219, 'Eversmoking Bottle', 3),
(220, 'Figurine of Wondrous Power, Golden Lions', 3),
(221, 'Bracers of Archery', 3),
(222, 'Mace of Terror', 3),
(223, 'Baba Yaga\'s Dancing Broom', 3),
(224, 'Ring of Warmth', 3),
(225, 'Slippers of Spider Climbing', 3),
(226, 'Staff of the Python', 3),
(227, 'Spell Scroll, 5th', 3),
(228, 'Potion of Flying', 3),
(229, 'Potion of Speed', 3),
(230, 'Feywild Shard', 3),
(231, 'Mariner\'s Armor', 3),
(232, 'Periapt of Health', 3),
(233, 'Potion of Poison', 3),
(234, 'Necklace of Adaptation', 3),
(235, 'Sword of Vengeance', 3),
(236, 'Mithral Half Plate', 3),
(237, 'Necklace of Fireballs', 3),
(238, 'Gem of Brightness', 3),
(239, 'Enspelled Staff (Level 3)', 3),
(240, 'Duplicitious Manuscript', 3),
(241, 'Wand of War Mage, +1', 3),
(242, 'Ioun Stone, Awareness', 3),
(243, 'Adamantine Half Plate', 3),
(244, 'Ventilating Lungs', 3),
(245, 'Eldritch Claw Tattoo', 3),
(246, 'Horseshoes of Speed', 4),
(247, 'Portable Hole', 4),
(248, 'Cloak of Displacement', 4),
(249, 'Periapt of Proof against Poison', 4),
(250, 'Wand of Fireballs', 4),
(251, 'Wand of Lightning Bolts', 4),
(252, 'Astral Shard', 4),
(253, 'Shadowfell Shard', 4),
(254, 'Fulminating Treatise', 4),
(255, 'Heart Weaver\'s Primer', 4),
(256, 'Libram of Souls and Flesh', 4),
(257, 'Planecaller\'s Codex', 4),
(258, 'Protective Verses', 4),
(259, 'Ring of Animal Influence', 4),
(260, 'Astromancy Archive', 4),
(261, 'Ring of Shooting Stars', 4),
(262, 'Potion of Giant Strength, Hill', 4),
(263, 'Eyes of Minute Seeing', 4),
(264, 'Eyes of the Eagle', 4),
(265, 'Gloves of Thievery', 4),
(266, 'Lantern of Revealing', 4),
(267, 'Ring of Swimming', 4),
(268, 'Ring of Water Walking', 4),
(269, 'Saddle of the Cavalier', 4),
(270, 'Ioun Stone, Agility', 4),
(271, 'Ioun Stone, Fortitude', 4),
(272, 'Ioun Stone, Insight', 4),
(273, 'Ioun Stone, Intellect', 4),
(274, 'Ioun Stone, Leadership', 4),
(275, 'Ioun Stone, Strength', 4),
(276, 'Catapult Munition', 4),
(277, 'Murgaxor\'s Elixir of Life', 4),
(278, 'Manual of Golems, Flesh', 4),
(279, 'Shadowfell Brand Tattoo', 4),
(280, 'Dreamlily', 4),
(281, 'Stone of Good Luck', 4),
(282, 'Boots of the Winterlands', 5),
(283, 'Far Realm Shard', 5),
(284, 'Mithral Plate', 5),
(285, 'Helm of the Gods', 5),
(286, 'Weapon, +1', 5),
(287, 'Wraps of Unarmed Power, +1', 5),
(288, 'Adamantine Plate', 5),
(289, 'Dragonhide Belt, +1', 5),
(290, 'Robe of Scintillating Colors', 5),
(291, 'Weapon of Warning', 5),
(292, 'Instrument of the Bards, Fochlucan Bandore', 5),
(293, 'Horseshoes of a Zephyr', 6),
(294, 'Sentinel Shield', 6),
(295, 'Cloak of Elvenkind', 6),
(296, 'Dancing Sword', 6),
(297, 'Gloves of Swimming and Climbing', 6),
(298, 'Pearl of Power', 6),
(299, 'Dust of Disappearance', 6),
(300, 'Barrier Tattoo, AC18', 6),
(301, 'Amulet of the Devout, +1', 6),
(302, 'Arcane Grimoire, +1', 6),
(303, 'Rhythm-Maker\'s Drum, +1', 6),
(304, 'Instrument of the Bards, Mac-Fuirmidh Cittern', 6),
(305, 'All-Purpose Tool, +1', 6),
(306, 'Mace of Disruption', 6),
(307, 'Cloak of Protection', 6),
(308, 'Outer Essence Shard, Evil', 6),
(309, 'Finder\'s Goggles', 6),
(310, 'Instrument of the Bards, Doss Lute', 6),
(311, 'Ioun Stone, Protection', 6),
(312, 'Bloodwell Vial, +1', 6),
(313, 'Moon Sickle, +1', 6),
(314, 'Rod of the Pact Keeper, +1', 6),
(315, 'Arcane Propulsion Arm', 6),
(316, 'Elemental Essence Shard, Fire', 7),
(317, 'Dagger of Venom', 7),
(318, 'Shield, +1', 7),
(319, 'Wand of War Mage, +2', 8),
(320, 'Crystalline Chronicle', 8),
(321, 'Ring of Protection', 8),
(322, 'Speaking Stone', 9),
(323, 'Folding Boat', 9),
(324, 'Trident of Fish Command', 9),
(325, 'Cloak of Arachnida', 9),
(326, 'Cloak of Arachnida', 9),
(327, 'Belt of Dwarvenkind', 9),
(328, 'Sword of Life Stealing', 10),
(329, 'Deck of Illusions', 10),
(330, 'Flying Chariot', 10),
(331, 'Armor, +1', 10),
(332, 'Elven Chain', 10),
(333, 'Two-Birds Sling', 10),
(334, 'Sword of Wounding', 11),
(335, 'Coiling Grasp Tattoo', 11),
(336, 'Instrument of the Bards, Canaith Mandolin', 11),
(337, 'Potion of Giant Strength, Frost/Stone', 11),
(338, 'Weapon, +2', 11),
(339, 'Wraps of Unarmed Power, +2', 11),
(340, 'Glamoured Studded Leather', 11),
(341, 'Cape of the Mountebank', 12),
(342, 'Figurine of Wondrous Power, Bronze Griffon', 12),
(343, 'Necklace of Prayer Beads, Summons Bead', 12),
(344, 'Quaal\'s Feather Token, Anchor', 12),
(345, 'Alchemical Compendium', 13),
(346, 'Sun Blade', 13),
(347, 'Spellwrought Tattoo, Lv4', 13),
(348, 'Wand of Binding', 13),
(349, 'Bag of Tricks', 13),
(350, 'Ammunition, +3', 13),
(351, 'Wings of Flying', 14),
(352, 'Bracers of Defense', 14),
(353, 'Elemental Essence Shard, Water', 14),
(354, 'Giant Slayer', 14),
(355, 'Atlas of Endless Horizons', 14),
(356, 'Dragonhide Belt, +2', 14),
(357, 'Flame Tongue', 14),
(358, 'Elemental Gem', 15),
(359, 'Ioun Stone, Absorption', 15),
(360, 'Winged Boots', 15),
(361, 'Arrow-Catching Shield', 15),
(362, 'Staff of Healing', 15),
(363, 'Sword of Sharpness', 15),
(364, 'Staff of Swarming Insects', 15),
(365, 'Brooch of Shielding', 15),
(366, 'Potion of Giant Strength, Fire', 15),
(367, 'Scimitar of Speed', 15),
(368, 'Cloak of the Bat', 16),
(369, 'Cube of Force', 16),
(370, 'Oil of Etherealness', 16),
(371, 'Quaal\'s Feather Token, Bird', 16),
(372, 'Spellwrought Tattoo, Lv5', 16),
(373, 'Dragon Slayer', 16),
(374, 'Amulet of the Devout, +2', 17),
(375, 'Arcane Grimoire, +2', 17),
(376, 'Rhythm-Maker\'s Drum, +2', 17),
(377, 'All-Purpose Tool, +2', 17),
(378, 'Ring of Evasion', 18),
(379, 'Enspelled Armor (Level 2)', 18),
(380, 'Enspelled Weapon (Level 2)', 18),
(381, 'Bloodwell Vial, +2', 18),
(382, 'Moon Sickle, +2', 18),
(383, 'Rod of the Pact Keeper, +2', 18),
(384, 'Rod of Alertness', 20),
(385, 'Armor of Resistance', 20),
(386, 'Mantle of Spell Resistance', 20),
(387, 'Ring of Free Action', 20),
(388, 'Shield of Missile Attraction', 20),
(389, 'Wand of Enemy Detection', 20),
(390, 'Potion of Vitality', 20),
(391, 'Spell Scroll, 6th', 20),
(392, 'Potion of Invulnerability', 20),
(393, 'Armor of Resistance, Leather', 20),
(394, 'Armor of Resistance, Chain Shirt', 20),
(395, 'Tentacle Rod', 20),
(396, 'Quarterstaff of the Acrobat', 20),
(397, 'Armor of Resistance, Breastplate', 20),
(398, 'Molten Bronze Skin Breastplate', 20),
(399, 'Vicious Weapon', 21),
(400, 'Shield of Far Sight', 21),
(401, 'Shield, +2', 21),
(402, 'Molten Broze Skin Half-Plate', 21),
(403, 'Molten Bronze Skin, Breastplate/Half-Plate/Plate', 21),
(404, 'Demon Armor', 21),
(405, 'Mace of Smiting', 21),
(406, 'Wheel of Wind and Water', 22),
(407, 'Immovable Rod', 22),
(408, 'Cloak of the Manta Ray', 22),
(409, 'Gauntlets of Ogre Power', 22),
(410, 'Headband of Intellect', 22),
(411, 'Armor of Resistance, Plate', 23),
(412, 'Molten Bronze Skin Plate', 23),
(413, 'Instrument of the Bards, Cli Lyre', 23),
(414, 'Potion of Greater Invisibility', 25),
(415, 'Spell Scroll, 7th', 25),
(416, 'Staff of Thunder and Lightning', 25),
(417, 'Silver Sword', 26),
(418, 'Wand of War Mage, +3', 26),
(419, 'Figurine of Wondrous Power, Obsidian Steed', 27),
(420, 'Enspelled Armor (Level 3)', 27),
(421, 'Enspelled Weapon (Level 3)', 27),
(422, 'Enspelled Staff (Level 4)', 27),
(423, 'Staff of Striking', 27),
(424, 'Dimensional Shackles', 30),
(425, 'Ring of Resistance', 30),
(426, 'Amulet of Health', 30),
(427, 'Wand of Polymorph', 30),
(428, 'Spell Scroll, 8th', 30),
(429, 'Outer Essence Shard, Lawful', 30),
(430, 'Dyrrn\'s Tentacle Whip', 30),
(431, 'Absorbing Tattoo', 30),
(432, 'Lute of Thunderous Thumping', 31),
(433, 'Armor, +2', 31),
(434, 'Lyre of Building', 34),
(435, 'Mirror of Life Trapping', 36),
(436, 'Broom of Flying', 37),
(437, 'Frost Brand', 39),
(438, 'Dwarven Thrower', 39),
(439, 'Oil of Sharpness', 39),
(440, 'Weapon, +3', 39),
(441, 'Wraps of Unarmed Power, +3', 39),
(442, 'Enspelled Staff (Level 5)', 40),
(443, 'Oathbow', 40),
(444, 'Dwarven Plate', 40),
(445, 'Dragonhide Belt, +3', 41),
(446, 'Lifewell Tattoo', 42),
(447, 'Robe of Stars', 43),
(448, 'Carpet of Flying, 3x5', 45),
(449, 'Nolzur\'s Marvelous Pigments', 45),
(450, 'Crystal Ball', 45),
(451, 'Ring of Telekinesis', 45),
(452, 'Belt of Giant Strength, Hill', 45),
(453, 'Bead of Force', 45),
(454, 'Staff of Fire', 45),
(455, 'Horn of Blasting', 49),
(456, 'Potion of Giant Strength, Cloud', 51),
(457, 'Dragon Scale Mail', 52),
(458, 'Necklace of Prayer Beads, Favor Bead', 53),
(459, 'Rod of Rulership', 53),
(460, 'Reveler\'s Concertina', 53),
(461, 'Amulet of the Devout, +3', 54),
(462, 'Arcane Grimoire, +3', 54),
(463, 'Rhythm-Maker\'s Drum, +3', 54),
(464, 'All-Purpose Tool, +3', 54),
(465, 'Bloodwell Vial, +3', 56),
(466, 'Moon Sickle, +3', 56),
(467, 'Rod of the Pact Keeper, +3', 56),
(468, 'Manual of Golems, Clay', 60),
(469, 'Potion of Longevity', 60),
(470, 'Shield, +3', 61),
(471, 'Necklace of Prayer Beads, Wind Walking Bead', 66),
(472, 'Rod of Absorption', 69),
(473, 'Shield of the Cavalier', 69),
(474, 'Bowl of Commanding Water Elementals', 80),
(475, 'Brazier of Commanding Fire Elementals', 80),
(476, 'Censer of Controlling Air Elementals', 80),
(477, 'Cube of Summoning', 80),
(478, 'Figurine of Wondrous Power, Marble Elephant', 80),
(479, 'Stone of Controlling Earth Elementals', 80),
(480, 'Gem of Seeing', 80),
(481, 'Staff of the Woodlands', 80),
(482, 'Staff of Charming', 81),
(483, 'Carpet of Flying, 4x6', 90),
(484, 'Candle of Invocation', 90),
(485, 'Belt of Giant Strength, Stone/Frost', 94),
(486, 'Helm of Teleportation', 100),
(487, 'Staff of Withering', 100),
(488, 'Living Gloves', 100),
(489, 'Manual of Golems, Stone', 120),
(490, 'Belt of Giant Strength, Fire', 126),
(491, 'Staff of Frost', 131),
(492, 'Ioun Stone, Reserve', 133),
(493, 'Wand of Paralysis', 133),
(494, 'Carpet of Flying, 5x7', 135),
(495, 'Robe of Eyes', 136),
(496, 'Wand of Wonder', 136),
(497, 'Manual of Bodily Health', 141),
(498, 'Manual of Gainful Exercise', 141),
(499, 'Manual of Quickness of Action', 141),
(500, 'Tome of Clear Thought', 141),
(501, 'Tome of Leadership and Influence', 141),
(502, 'Tome of Understanding', 141),
(503, 'Ring of Mind Shielding', 150),
(504, 'Rod of Security', 150),
(505, 'Carpet of Flying, 6x9', 155),
(506, 'Horn of Valhalla, Silver', 160),
(507, 'Staff of Power', 164),
(508, 'Ring of Spell Storing', 166),
(509, 'Thunderous Greatclub', 179),
(510, 'Enspelled Armor (Level 4)', 180),
(511, 'Enspelled Weapon (Level 4)', 180),
(512, 'Manual of Golems, Iron', 180),
(513, 'Daern\'s Instant Fortress', 200),
(514, 'Spellguard Shield', 200),
(515, 'Enspelled Armor (Level 5)', 225),
(516, 'Enspelled Weapon (Level 5)', 225),
(517, 'Instrument of the Bards, Anstruth Harp', 227),
(518, 'Horn of Valhalla, Brass', 240),
(519, 'Amulet of the Planes', 250),
(520, 'Ring of Regeneration', 250),
(521, 'Nine Lives Stealer', 281),
(522, 'Helm of Brilliance', 348),
(523, 'Horn of Valhalla, Bronze', 420),
(524, 'Efreeti Bottle', 540);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `StatusID` tinyint(3) UNSIGNED NOT NULL,
  `Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`StatusID`, `Status`) VALUES
(1, 'Active'),
(7, 'Banished'),
(8, 'Deceased'),
(6, 'Imprisoned'),
(2, 'Resting'),
(5, 'Retired'),
(9, 'Transcendent'),
(4, 'Travelling'),
(3, 'Unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE `theme` (
  `ThemeID` smallint(5) UNSIGNED NOT NULL,
  `ThemeName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` smallint(5) UNSIGNED NOT NULL COMMENT '0-65535 users',
  `UserName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `UserPassword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `UserEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `DMUntil` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Define a User of the website';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `UserPassword`, `UserEmail`, `DMUntil`) VALUES
(0, 'DICE', '$2y$10$3tam0VOyB2fBcJOUfmCPuOqZ3qBWK1oU.Gwqy3YQRoHVyJtCyshdO', 'd.i.c.e.mmu.cyber@gmail.com', NULL),
(1, 'AyeZa', '$2y$10$U.QxZRUK.L7KC0DORcGFXeybt5rC8VI44Ze1P6yNV8cHihNlMei6q', 'ayesha00zahra@gmail.com', '2026-09-30 23:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `username`
--

CREATE TABLE `username` (
  `NameID` smallint(5) UNSIGNED NOT NULL,
  `Name` varchar(100) NOT NULL,
  `UserID` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `username`
--

INSERT INTO `username` (`NameID`, `Name`, `UserID`) VALUES
(0, 'Dive Into Imagination For Creative Entertainment', 0),
(1, 'Ayesha Zahra', 1),
(2, 'Chang Tai Jern', NULL),
(3, 'Damien Yap Zhi Khuen', NULL),
(4, 'Isabel Yap Quan-E', NULL),
(5, 'Khai', NULL),
(6, 'Muhammad Hafiz bin Mohd Khalid', NULL),
(7, 'Muhd Shaqimee Bin Shahrilnizam', NULL),
(8, 'Neo Zhi Ming', NULL),
(9, 'Nirvan A/L Subramaniam', NULL),
(10, 'Nur. Rahmah', NULL),
(11, 'Ong Ting Shiun', NULL),
(12, 'Syef', NULL),
(13, 'Tham Joe Ming', NULL),
(14, 'Yousef Mohamed', NULL),
(15, 'Chew Xie Yang', NULL),
(16, 'Nurul Iman binti Samijan', NULL),
(17, 'Imran Faizal', NULL),
(18, 'Sharvesh A/L Vijayagomaran', NULL),
(19, 'Khairil Anwar Rusli', NULL),
(20, 'Muhammad Sharqir', NULL),
(21, 'Nabil Faris Khan', NULL),
(22, 'Muhammad Syafiq', NULL),
(23, 'Luqman Amsyar', NULL),
(24, 'Muhammad Imran Hakimi', NULL),
(25, 'Alia A.', NULL),
(26, 'Nottic', NULL),
(27, 'Arif Hakimi  [Online]', NULL),
(28, 'Hizuddin', NULL),
(29, 'Erin Sue-Yin', NULL),
(30, 'Kristina Wolf', NULL),
(31, 'Sofea Aliana', NULL),
(32, 'Izhan Hasani Bin Harlizan', NULL),
(33, 'Lee Wen Kang (Alex)', NULL),
(34, 'Faris Hanafi', NULL),
(35, 'Adrel Syafitri bin Mohamed Faris', NULL),
(36, 'Azril bin Sudirman', NULL),
(37, 'Muhammad Umar Haziq Syah', NULL),
(38, 'Razin Rahimi Bin Ahmad Zulkefeee', NULL),
(39, 'Muhammad Saiful Anuar Bin Mohd Farid', NULL),
(40, 'Jarett Tan Yuan Sen', NULL),
(41, 'Akhmad Farhan Ramadhan', NULL),
(42, 'Jaiypnthraaj Mankan', NULL),
(43, 'Iqbal bin Norrashid', NULL),
(44, 'Azrul Idzwan', NULL),
(45, 'Harry H.', NULL),
(46, 'Jordan W.', NULL),
(47, 'Razein Zaffri', NULL),
(48, 'Teh Zhao Jin', NULL),
(49, 'Izzminhal Akmal bin Nurhisyam', NULL),
(50, 'Yeap Yin Zhi', NULL),
(51, 'Alan Rrifdi bin Munasor', NULL),
(52, 'Muhammad albukhari bin Norazmi', NULL),
(53, 'Woon Yu Han', NULL),
(54, 'Bryan Loh Guo Sheng', NULL),
(55, 'Mikael Akif', NULL),
(56, 'Muhammad Shahmeer', NULL),
(57, 'Nik Mohamed Ahmas', NULL),
(58, 'Muhammad Irfan Tang', NULL),
(59, 'Nor Iffah Sabrina bt Nor Azizz', NULL),
(60, 'Najihah Shukriena bt Saat Shukri', NULL),
(61, 'Safwan Al-Jufit', NULL),
(62, 'Mikail Muars Mohd Musyiri', NULL),
(63, 'Gan Kai Li Kelly', NULL),
(64, 'Lau Jia Han', NULL),
(65, 'Mu\'az bin Zulkifli', NULL),
(66, 'Nur Izzah Zahna bt Mohd Fazli', NULL),
(67, 'Azhmat Hisham bin Ahmad', NULL),
(68, 'Ammar Zaqwan bin Afdzan Rizal', NULL),
(69, 'Syahreza Azha bin Amer', NULL),
(70, 'Adam Adrisheh bin Ahmad Azlishah', NULL),
(71, 'Yeoh Soon Jie', NULL),
(72, 'Hana Humairah binti Zoal Fadli', NULL),
(73, 'Joy Yu Wen', NULL),
(74, 'M. Phoebe. K', NULL),
(75, 'Muhammad Amirul bin Jais', NULL),
(76, 'Muhammad Arif Hakimi bin As\'at', NULL),
(77, 'Muhammad Mirza bin Mohd Mizacazrin', NULL),
(78, 'Liew Jin Hao ', NULL),
(79, 'Lim Keith Yen', NULL),
(80, 'Stanley Tan Ken Seong', NULL),
(81, 'Muhd Adib Nu\'man bin Kamarul Anuar', NULL),
(82, 'Jeffrey Low', NULL),
(83, 'Mysara', NULL),
(84, 'Izzah Zahirah', NULL),
(85, 'Lutfi Al-Hadi Aidil', NULL),
(86, 'Alif Aql', NULL),
(87, 'Aishath Rif\'aa Mohamed', NULL),
(88, 'Tun Muhammad Zharif', NULL),
(89, 'Muhammad Idlan Haziq', NULL),
(90, 'Hakimi', NULL),
(91, 'Nadja Caitlin', NULL),
(92, 'Hafizuddin Hayat', NULL),
(93, 'Shahizaq Faiez', NULL),
(94, 'Muhammad Aqil Aminuddin', NULL),
(95, 'Sabrina Sulit', NULL),
(96, 'Arianna Harisya binti Azril', NULL),
(97, 'Arianne Van Lutam', NULL),
(98, 'Naufal bin Zamanhari', NULL),
(99, 'Shaqeel Iman', NULL),
(100, 'Aleia Auzani', NULL),
(101, 'Muhammad Azzam Aqmal', NULL),
(102, 'Siti Aisyah Sofea binti Azizul', NULL),
(103, 'Wan Arif Danial', NULL),
(104, 'Chai Yao Wei', NULL),
(105, 'Ahmad Wafiq', NULL),
(106, 'Roy', NULL),
(107, 'Daniel Aiman', NULL),
(108, 'Eugene Fung', NULL),
(109, 'Kishen Kumar', NULL),
(110, 'Syauqi Amul', NULL),
(111, 'Wong Qi Wen', NULL),
(112, 'Iain Goh Tzin Sheng', NULL),
(113, 'Mohammad Syahmi Naq', NULL),
(114, 'Wong Hong Feng Vidon', NULL),
(115, 'Wan (Zii)', NULL),
(116, 'Sebastian Loh', NULL),
(117, 'Jovian Nathan (Jovi)', NULL),
(118, 'Mohamed Osman Salaheldin Mohamed Osman Ahmed', NULL),
(119, 'Esvan Rao A/L Perasath Raw', NULL),
(120, 'Muhammad Afiq Haikal bin Azari', NULL),
(121, 'Zuhair Zahin bin Zulkifli', NULL),
(122, 'Muhammad Adam Iqbal Bin Hafiz', NULL),
(123, 'Imran Faris Bin Md Yusri', NULL),
(124, 'Mohammad Fard bin Mohammad Fariz', NULL),
(125, 'Adlina Rasiah bt Kamarul Ariffin', NULL),
(126, 'Muhamad Izzat Abidin', NULL),
(127, 'Wei Siang (Jason)', NULL),
(128, 'Ives (Yuise)', NULL),
(129, 'Muhammad Aladdin', NULL),
(130, 'Chee Khun Chen', NULL),
(131, 'Muhammad Firdaus Hakimi', NULL),
(132, 'Mohamad Hurish Irfan', NULL),
(133, 'Lee Jun Le', NULL),
(134, 'Muhammad Fayeed Afiq bin Roslee', NULL),
(135, 'Yin Xuan (Melody)', NULL),
(136, 'Sasmithamalar', NULL),
(137, 'Nur Alea Elena (Alea)', NULL),
(138, 'Sutan Mohamed Amin bin Yuaz Bahtiar (Amin)', NULL),
(139, 'Yousef Elsayed', NULL),
(140, 'Tareq Ahmed Abdelsalam Ali Ibrahim (Tareq)', NULL),
(141, 'Imam Arif Mustafa bin Abdul Rahman', NULL),
(142, 'Guo Quan', NULL),
(143, 'Warda Ali', NULL),
(144, 'Bryan Ng (HappyOnline)', NULL),
(145, 'Jackreon Chan', NULL),
(146, 'Leilani (Lei)', NULL),
(147, 'Ib', NULL),
(148, 'M. Murtadi', NULL),
(149, 'Teoh Shi Zuo', NULL),
(150, 'Justin', NULL),
(151, 'Puteri Alia Hani', NULL),
(152, 'Cheng Yu Ming', NULL),
(153, 'Danial Aqasha bin Harmi Sazwane', NULL),
(154, 'Aqmar Zuhairi bin Azman', NULL),
(155, 'Kiasati Dia Najma binti Anderanto Aisau Wudhana', NULL),
(156, 'Goh Jing Wen', NULL),
(157, 'Amzar Rusyaid bin Azman', NULL),
(158, 'Muhammad Nawfal Mikhael Bin Mohd Ridzwan', NULL),
(159, 'Zul Fadhli Bin Zaiman', NULL),
(160, 'Kenneth Tham Yu Jiang', NULL),
(161, 'Cheah Xin Yan', NULL),
(162, 'Muhammad Arfan Bin Shahnaz Fariz', NULL),
(163, 'Izz Haikal Bin Saiful Azree', NULL),
(164, 'Muhammad Hadiff', NULL),
(165, 'Fai', NULL),
(166, 'Melvin', NULL),
(168, 'Muhammad Raihan', NULL),
(169, 'Muhmamad Syahmir Airel Bin Jamal Ab Nasir', NULL),
(170, 'Mohammad Imtiyaz Bin Mohd Fazni', NULL),
(171, 'Nazul \'Heaven\' Faridth Firdaous', NULL),
(172, 'Wong Shan Kang', NULL),
(173, 'Muhammad Idzlan Irfan Bin Normohamed Putra', NULL),
(174, 'Eldric Khong Zheng Yin', NULL),
(175, 'Luqman Hakim Bin Mohammad Zikri', NULL),
(176, 'Nuqman Alif Syakir Bin Sherifuddin', NULL),
(177, 'Chien Zi Lun ', NULL),
(178, 'Daniel Xiao Yao Look', NULL),
(179, 'Munir Bin Noorazman', NULL),
(180, 'Ng Zhen Hong', NULL),
(181, 'Yuen Theng', NULL),
(182, 'Viro', NULL),
(183, 'Aiden Ridley', NULL),
(184, 'Charlotte C.', NULL),
(185, 'Qiao Qing', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_consent`
--

CREATE TABLE `user_consent` (
  `ConsentID` int(10) UNSIGNED NOT NULL,
  `UserID` smallint(5) UNSIGNED NOT NULL,
  `WarningID` tinyint(3) UNSIGNED NOT NULL,
  `Level` enum('Green','Yellow','Red','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warning`
--

CREATE TABLE `warning` (
  `WarningID` tinyint(3) UNSIGNED NOT NULL,
  `category` varchar(50) NOT NULL,
  `subcategory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warning`
--

INSERT INTO `warning` (`WarningID`, `category`, `subcategory`) VALUES
(1, 'Horror', 'Harm to Children'),
(2, 'Horror', 'Harm to Animals'),
(3, 'Horror', 'Gore'),
(4, 'Horror', 'Graphic Dismemberment'),
(5, 'Horror', 'Body Transformation'),
(6, 'Horror', 'Insects or vermin'),
(7, 'Horror', 'Other'),
(8, 'Romance', 'Off Screen'),
(9, 'Romance', 'Explicit'),
(10, 'Romance', 'Player-to-DM'),
(11, 'Sex', 'Off Screen'),
(12, 'Sex', 'Explicit'),
(13, 'Sex', 'Player-to-DM'),
(14, 'Social Issues', 'Racism'),
(15, 'Social Issues', 'Sexism'),
(16, 'Social Issues', 'LGBTQ+ discrimination'),
(17, 'Social Issues', 'Real-Life Religion'),
(18, 'Social Issues', 'Other'),
(19, 'Trauma', 'Terminal illness'),
(20, 'Trauma', 'Suicide'),
(21, 'Trauma', 'Self-harm'),
(22, 'Trauma', 'Eating Disorders'),
(23, 'Trauma', 'Pregnancy complications'),
(24, 'Trauma', 'Other'),
(25, 'Violence', 'Abuse'),
(26, 'Violence', 'Torture'),
(27, 'Violence', 'Sexual Assault'),
(28, 'Violence', 'Terrorism'),
(29, 'Violence', 'War'),
(30, 'Violence', 'Mass Casualties'),
(31, 'Violence', 'Human Trafficking'),
(32, 'Violence', 'Other');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board_card`
--
ALTER TABLE `board_card`
  ADD PRIMARY KEY (`CardID`),
  ADD KEY `SectionID` (`SectionID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `board_note`
--
ALTER TABLE `board_note`
  ADD PRIMARY KEY (`NoteID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `board_section`
--
ALTER TABLE `board_section`
  ADD PRIMARY KEY (`SectionID`);

--
-- Indexes for table `dekkara_event`
--
ALTER TABLE `dekkara_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_start_day` (`start_day`);

--
-- Indexes for table `dekkara_month`
--
ALTER TABLE `dekkara_month`
  ADD PRIMARY KEY (`month_num`);

--
-- Indexes for table `dndclass`
--
ALTER TABLE `dndclass`
  ADD PRIMARY KEY (`ClassID`);

--
-- Indexes for table `dndclasssub`
--
ALTER TABLE `dndclasssub`
  ADD PRIMARY KEY (`SubClassID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `dndspecies`
--
ALTER TABLE `dndspecies`
  ADD PRIMARY KEY (`SpeciesID`);

--
-- Indexes for table `dndspeciessub`
--
ALTER TABLE `dndspeciessub`
  ADD PRIMARY KEY (`SubSpeciesID`),
  ADD KEY `fkSpeciesID` (`SpeciesID`);

--
-- Indexes for table `environmenttypes`
--
ALTER TABLE `environmenttypes`
  ADD PRIMARY KEY (`EnvironmentID`);

--
-- Indexes for table `faction`
--
ALTER TABLE `faction`
  ADD PRIMARY KEY (`FactionID`),
  ADD UNIQUE KEY `FactionName_2` (`FactionName`),
  ADD KEY `FactionName` (`FactionName`);

--
-- Indexes for table `faction_npc`
--
ALTER TABLE `faction_npc`
  ADD PRIMARY KEY (`FactionNPCID`),
  ADD KEY `FactionID` (`FactionID`),
  ADD KEY `NPCID` (`NPCID`);

--
-- Indexes for table `faction_pc`
--
ALTER TABLE `faction_pc`
  ADD PRIMARY KEY (`FactionPCID`),
  ADD KEY `FactionID` (`FactionID`),
  ADD KEY `NPCID` (`PCID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`InventoryID`),
  ADD KEY `PCID` (`PCID`);

--
-- Indexes for table `inventory_coin`
--
ALTER TABLE `inventory_coin`
  ADD PRIMARY KEY (`InventoryCoinID`),
  ADD KEY `InventoryID` (`InventoryID`);

--
-- Indexes for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD PRIMARY KEY (`InventoryItemID`),
  ADD UNIQUE KEY `InventoryID_ItemID` (`InventoryID`,`ItemID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indexes for table `location_building`
--
ALTER TABLE `location_building`
  ADD PRIMARY KEY (`BuildingID`),
  ADD UNIQUE KEY `BuildingName` (`BuildingName`,`SettlementID`),
  ADD KEY `LocationName` (`BuildingName`),
  ADD KEY `LocationID` (`SettlementID`);

--
-- Indexes for table `location_buildingtype`
--
ALTER TABLE `location_buildingtype`
  ADD PRIMARY KEY (`TypeID`),
  ADD KEY `TypeName` (`TypeName`),
  ADD KEY `LocationID` (`BuildingID`);

--
-- Indexes for table `location_region`
--
ALTER TABLE `location_region`
  ADD PRIMARY KEY (`RegionID`),
  ADD UNIQUE KEY `UqLocationName` (`RegionName`),
  ADD KEY `LocationName` (`RegionName`);

--
-- Indexes for table `location_regiontype`
--
ALTER TABLE `location_regiontype`
  ADD PRIMARY KEY (`TypeID`),
  ADD KEY `TypeName` (`TypeName`),
  ADD KEY `LocationID` (`RegionID`);

--
-- Indexes for table `location_settlement`
--
ALTER TABLE `location_settlement`
  ADD PRIMARY KEY (`SettlementID`),
  ADD UNIQUE KEY `SettlementName` (`SettlementName`,`RegionID`),
  ADD KEY `LocationName` (`SettlementName`),
  ADD KEY `regionID` (`RegionID`);

--
-- Indexes for table `location_settlementtype`
--
ALTER TABLE `location_settlementtype`
  ADD PRIMARY KEY (`TypeID`),
  ADD KEY `TypeName` (`TypeName`),
  ADD KEY `LocationID` (`SettlementID`);

--
-- Indexes for table `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`MissionID`),
  ADD KEY `MissionName` (`MissionName`);

--
-- Indexes for table `npc`
--
ALTER TABLE `npc`
  ADD PRIMARY KEY (`NPCID`),
  ADD KEY `PCName` (`NPCName`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `InventoryID` (`InventoryID`),
  ADD KEY `NPCStatus` (`NPCStatus`);

--
-- Indexes for table `npc_bio`
--
ALTER TABLE `npc_bio`
  ADD PRIMARY KEY (`BioID`),
  ADD UNIQUE KEY `NPCID` (`NPCID`),
  ADD KEY `fkNPCSpeciesID` (`SpeciesID`),
  ADD KEY `fkNPCSubSpeciesID` (`SubSpeciesID`);

--
-- Indexes for table `npc_class`
--
ALTER TABLE `npc_class`
  ADD KEY `fkClassNPCID` (`NPCID`),
  ADD KEY `fkNPCdndclass1` (`Class1`),
  ADD KEY `fkNPCdndclass2` (`Class2`),
  ADD KEY `fkNPCdndclass3` (`Class3`),
  ADD KEY `fkNPCsubclass1` (`SubClass1`),
  ADD KEY `fkNPCsubclass2` (`SubClass2`),
  ADD KEY `fkNPCsubclass3` (`SubClass3`);

--
-- Indexes for table `npc_info`
--
ALTER TABLE `npc_info`
  ADD PRIMARY KEY (`NPCInfoID`),
  ADD KEY `NPCID` (`NPCID`);

--
-- Indexes for table `npc_stat`
--
ALTER TABLE `npc_stat`
  ADD PRIMARY KEY (`NPCStatID`),
  ADD UNIQUE KEY `NPCID` (`NPCID`);

--
-- Indexes for table `party`
--
ALTER TABLE `party`
  ADD PRIMARY KEY (`PartyID`),
  ADD KEY `SessionID` (`SessionID`);

--
-- Indexes for table `party_member`
--
ALTER TABLE `party_member`
  ADD PRIMARY KEY (`PartyID`,`PCID`);

--
-- Indexes for table `pc`
--
ALTER TABLE `pc`
  ADD PRIMARY KEY (`PCID`),
  ADD KEY `PCName` (`PCName`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `InventoryID` (`InventoryID`);

--
-- Indexes for table `pc_bio`
--
ALTER TABLE `pc_bio`
  ADD PRIMARY KEY (`BioID`),
  ADD UNIQUE KEY `NPCID` (`PCID`),
  ADD KEY `fkNPCSpeciesID` (`SpeciesID`),
  ADD KEY `fkNPCSubSpeciesID` (`SubSpeciesID`);

--
-- Indexes for table `pc_class`
--
ALTER TABLE `pc_class`
  ADD KEY `fkClassNPCID` (`PCID`),
  ADD KEY `fkPCdndclass1` (`Class1`),
  ADD KEY `fkPCdndclass2` (`Class2`),
  ADD KEY `fkPCdndclass3` (`Class3`),
  ADD KEY `fkPCsubclass1` (`SubClass1`),
  ADD KEY `fkPCsubclass2` (`SubClass2`),
  ADD KEY `fkPCsubclass3` (`SubClass3`);

--
-- Indexes for table `pc_stat`
--
ALTER TABLE `pc_stat`
  ADD PRIMARY KEY (`PCStatID`),
  ADD UNIQUE KEY `PCID` (`PCID`);

--
-- Indexes for table `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`QuestID`),
  ADD KEY `QuestName` (`QuestName`),
  ADD KEY `QuestTier` (`QuestTier`),
  ADD KEY `QuestLevel` (`QuestLevel`);

--
-- Indexes for table `quest_building`
--
ALTER TABLE `quest_building`
  ADD PRIMARY KEY (`QuestLocationID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `BuildingID` (`BuildingID`);

--
-- Indexes for table `quest_environment`
--
ALTER TABLE `quest_environment`
  ADD PRIMARY KEY (`QuestEnvironmentID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `EnviromentID` (`EnviromentID`);

--
-- Indexes for table `quest_mission`
--
ALTER TABLE `quest_mission`
  ADD PRIMARY KEY (`QuestMissionID`),
  ADD KEY `MissionID` (`MissionID`),
  ADD KEY `QuestID` (`QuestID`);

--
-- Indexes for table `quest_npc`
--
ALTER TABLE `quest_npc`
  ADD PRIMARY KEY (`QuestNPCID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `NPCID` (`NPCID`);

--
-- Indexes for table `quest_region`
--
ALTER TABLE `quest_region`
  ADD PRIMARY KEY (`QuestLocationID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `RegionID` (`RegionID`);

--
-- Indexes for table `quest_rewards`
--
ALTER TABLE `quest_rewards`
  ADD PRIMARY KEY (`RewardID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `idx_reward_type` (`RewardType`);

--
-- Indexes for table `quest_settlement`
--
ALTER TABLE `quest_settlement`
  ADD PRIMARY KEY (`QuestLocationID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `SettlementID` (`SettlementID`);

--
-- Indexes for table `quest_theme`
--
ALTER TABLE `quest_theme`
  ADD PRIMARY KEY (`QuestThemeID`),
  ADD KEY `ThemeID` (`ThemeID`),
  ADD KEY `QuestID` (`QuestID`);

--
-- Indexes for table `quest_warning`
--
ALTER TABLE `quest_warning`
  ADD PRIMARY KEY (`QuestWarningID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `WarningID` (`WarningID`);

--
-- Indexes for table `relationship`
--
ALTER TABLE `relationship`
  ADD PRIMARY KEY (`RelationshipID`),
  ADD KEY `SubjectType` (`SubjectType`),
  ADD KEY `SubjectID` (`SubjectID`),
  ADD KEY `TargetType` (`TargetType`),
  ADD KEY `TargetID` (`TargetID`),
  ADD KEY `SubjectID_2` (`SubjectID`),
  ADD KEY `SubjectType_2` (`SubjectType`),
  ADD KEY `TargetType_2` (`TargetType`),
  ADD KEY `BubbleID` (`BubbleID`);

--
-- Indexes for table `relationship_bubble`
--
ALTER TABLE `relationship_bubble`
  ADD PRIMARY KEY (`BubbleID`),
  ADD UNIQUE KEY `RelationshipName` (`BubbleName`),
  ADD KEY `RelationshipCategory` (`BubbleCategory`);

--
-- Indexes for table `rewardtypes`
--
ALTER TABLE `rewardtypes`
  ADD PRIMARY KEY (`TypeID`),
  ADD KEY `TypeName` (`TypeName`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`SessionID`),
  ADD KEY `QuestID` (`QuestID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `session_feedback_dm`
--
ALTER TABLE `session_feedback_dm`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `SessionID` (`SessionID`),
  ADD KEY `DMID` (`DMID`);

--
-- Indexes for table `session_feedback_module`
--
ALTER TABLE `session_feedback_module`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `SessionID` (`SessionID`);

--
-- Indexes for table `session_feedback_player`
--
ALTER TABLE `session_feedback_player`
  ADD PRIMARY KEY (`FeedbackID`);

--
-- Indexes for table `session_reward`
--
ALTER TABLE `session_reward`
  ADD PRIMARY KEY (`SessionRewardID`),
  ADD KEY `PCID` (`PCID`),
  ADD KEY `SessionID` (`SessionID`),
  ADD KEY `fkSessionRewardType` (`RewardTypeID`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `ItemName` (`ItemName`),
  ADD KEY `ItemPrice` (`ItemPrice`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`StatusID`),
  ADD KEY `Status` (`Status`);

--
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`ThemeID`),
  ADD UNIQUE KEY `ThemeName` (`ThemeName`),
  ADD UNIQUE KEY `ThemeName_2` (`ThemeName`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `UserName_2` (`UserName`),
  ADD UNIQUE KEY `UserEmail` (`UserEmail`);

--
-- Indexes for table `username`
--
ALTER TABLE `username`
  ADD PRIMARY KEY (`NameID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `user_consent`
--
ALTER TABLE `user_consent`
  ADD PRIMARY KEY (`ConsentID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ContentID` (`WarningID`);

--
-- Indexes for table `warning`
--
ALTER TABLE `warning`
  ADD PRIMARY KEY (`WarningID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board_card`
--
ALTER TABLE `board_card`
  MODIFY `CardID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `board_note`
--
ALTER TABLE `board_note`
  MODIFY `NoteID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `board_section`
--
ALTER TABLE `board_section`
  MODIFY `SectionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dekkara_event`
--
ALTER TABLE `dekkara_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `environmenttypes`
--
ALTER TABLE `environmenttypes`
  MODIFY `EnvironmentID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `faction`
--
ALTER TABLE `faction`
  MODIFY `FactionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faction_npc`
--
ALTER TABLE `faction_npc`
  MODIFY `FactionNPCID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faction_pc`
--
ALTER TABLE `faction_pc`
  MODIFY `FactionPCID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `InventoryID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_coin`
--
ALTER TABLE `inventory_coin`
  MODIFY `InventoryCoinID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_item`
--
ALTER TABLE `inventory_item`
  MODIFY `InventoryItemID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_building`
--
ALTER TABLE `location_building`
  MODIFY `BuildingID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `location_buildingtype`
--
ALTER TABLE `location_buildingtype`
  MODIFY `TypeID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_region`
--
ALTER TABLE `location_region`
  MODIFY `RegionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `location_regiontype`
--
ALTER TABLE `location_regiontype`
  MODIFY `TypeID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_settlement`
--
ALTER TABLE `location_settlement`
  MODIFY `SettlementID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `location_settlementtype`
--
ALTER TABLE `location_settlementtype`
  MODIFY `TypeID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mission`
--
ALTER TABLE `mission`
  MODIFY `MissionID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `npc`
--
ALTER TABLE `npc`
  MODIFY `NPCID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `npc_bio`
--
ALTER TABLE `npc_bio`
  MODIFY `BioID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `npc_info`
--
ALTER TABLE `npc_info`
  MODIFY `NPCInfoID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `npc_stat`
--
ALTER TABLE `npc_stat`
  MODIFY `NPCStatID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `party`
--
ALTER TABLE `party`
  MODIFY `PartyID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pc`
--
ALTER TABLE `pc`
  MODIFY `PCID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pc_bio`
--
ALTER TABLE `pc_bio`
  MODIFY `BioID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pc_stat`
--
ALTER TABLE `pc_stat`
  MODIFY `PCStatID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quest`
--
ALTER TABLE `quest`
  MODIFY `QuestID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quest_building`
--
ALTER TABLE `quest_building`
  MODIFY `QuestLocationID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_environment`
--
ALTER TABLE `quest_environment`
  MODIFY `QuestEnvironmentID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_mission`
--
ALTER TABLE `quest_mission`
  MODIFY `QuestMissionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_npc`
--
ALTER TABLE `quest_npc`
  MODIFY `QuestNPCID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_region`
--
ALTER TABLE `quest_region`
  MODIFY `QuestLocationID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_rewards`
--
ALTER TABLE `quest_rewards`
  MODIFY `RewardID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_settlement`
--
ALTER TABLE `quest_settlement`
  MODIFY `QuestLocationID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_theme`
--
ALTER TABLE `quest_theme`
  MODIFY `QuestThemeID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quest_warning`
--
ALTER TABLE `quest_warning`
  MODIFY `QuestWarningID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `relationship`
--
ALTER TABLE `relationship`
  MODIFY `RelationshipID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rewardtypes`
--
ALTER TABLE `rewardtypes`
  MODIFY `TypeID` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `session_reward`
--
ALTER TABLE `session_reward`
  MODIFY `SessionRewardID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `StatusID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `ThemeID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_consent`
--
ALTER TABLE `user_consent`
  MODIFY `ConsentID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warning`
--
ALTER TABLE `warning`
  MODIFY `WarningID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dndclasssub`
--
ALTER TABLE `dndclasssub`
  ADD CONSTRAINT `fkSubClassID` FOREIGN KEY (`ClassID`) REFERENCES `dndclass` (`ClassID`);

--
-- Constraints for table `dndspeciessub`
--
ALTER TABLE `dndspeciessub`
  ADD CONSTRAINT `fkSpeciesID` FOREIGN KEY (`SpeciesID`) REFERENCES `dndspecies` (`SpeciesID`);

--
-- Constraints for table `faction_npc`
--
ALTER TABLE `faction_npc`
  ADD CONSTRAINT `fkNPCFactionID` FOREIGN KEY (`FactionID`) REFERENCES `faction` (`FactionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faction_pc`
--
ALTER TABLE `faction_pc`
  ADD CONSTRAINT `fkFactionPCID` FOREIGN KEY (`PCID`) REFERENCES `pc` (`PCID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCFactionID` FOREIGN KEY (`FactionID`) REFERENCES `faction` (`FactionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory_coin`
--
ALTER TABLE `inventory_coin`
  ADD CONSTRAINT `fkCoinInventoryID` FOREIGN KEY (`InventoryID`) REFERENCES `inventory` (`InventoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD CONSTRAINT `fkItemInventoryID` FOREIGN KEY (`InventoryID`) REFERENCES `inventory` (`InventoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkinventoryItemID` FOREIGN KEY (`ItemID`) REFERENCES `shop` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `location_buildingtype`
--
ALTER TABLE `location_buildingtype`
  ADD CONSTRAINT `BuilldingFK` FOREIGN KEY (`BuildingID`) REFERENCES `location_building` (`BuildingID`);

--
-- Constraints for table `location_regiontype`
--
ALTER TABLE `location_regiontype`
  ADD CONSTRAINT `fk` FOREIGN KEY (`RegionID`) REFERENCES `location_region` (`RegionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `location_settlement`
--
ALTER TABLE `location_settlement`
  ADD CONSTRAINT `regionID` FOREIGN KEY (`RegionID`) REFERENCES `location_region` (`RegionID`);

--
-- Constraints for table `location_settlementtype`
--
ALTER TABLE `location_settlementtype`
  ADD CONSTRAINT `SettlementID` FOREIGN KEY (`SettlementID`) REFERENCES `location_settlement` (`SettlementID`);

--
-- Constraints for table `npc`
--
ALTER TABLE `npc`
  ADD CONSTRAINT `fkNPCStatusID` FOREIGN KEY (`NPCStatus`) REFERENCES `status` (`StatusID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fknpcInventoryID` FOREIGN KEY (`InventoryID`) REFERENCES `inventory` (`InventoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fknpcUserID` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `npc_bio`
--
ALTER TABLE `npc_bio`
  ADD CONSTRAINT `fkNPCSpeciesID` FOREIGN KEY (`SpeciesID`) REFERENCES `dndspecies` (`SpeciesID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkNPCSubSpeciesID` FOREIGN KEY (`SubSpeciesID`) REFERENCES `dndspeciessub` (`SubSpeciesID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkbioNPCID` FOREIGN KEY (`NPCID`) REFERENCES `npc` (`NPCID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `npc_class`
--
ALTER TABLE `npc_class`
  ADD CONSTRAINT `fkClassNPCID` FOREIGN KEY (`NPCID`) REFERENCES `npc` (`NPCID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkNPCdndclass1` FOREIGN KEY (`Class1`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkNPCdndclass2` FOREIGN KEY (`Class2`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkNPCdndclass3` FOREIGN KEY (`Class3`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkNPCsubclass1` FOREIGN KEY (`SubClass1`) REFERENCES `dndclasssub` (`SubClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkNPCsubclass2` FOREIGN KEY (`SubClass2`) REFERENCES `dndclasssub` (`SubClassID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fkNPCsubclass3` FOREIGN KEY (`SubClass3`) REFERENCES `dndclasssub` (`SubClassID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `npc_info`
--
ALTER TABLE `npc_info`
  ADD CONSTRAINT `fkInfoNPCID` FOREIGN KEY (`NPCID`) REFERENCES `npc` (`NPCID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `npc_stat`
--
ALTER TABLE `npc_stat`
  ADD CONSTRAINT `fkStatNPCID` FOREIGN KEY (`NPCID`) REFERENCES `npc` (`NPCID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `party`
--
ALTER TABLE `party`
  ADD CONSTRAINT `fkSessionParty` FOREIGN KEY (`SessionID`) REFERENCES `session` (`SessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `party_member`
--
ALTER TABLE `party_member`
  ADD CONSTRAINT `fkPartyID` FOREIGN KEY (`PartyID`) REFERENCES `party` (`PartyID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPartyPCID` FOREIGN KEY (`PCID`) REFERENCES `pc` (`PCID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pc`
--
ALTER TABLE `pc`
  ADD CONSTRAINT `fkPCUserID` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pc_bio`
--
ALTER TABLE `pc_bio`
  ADD CONSTRAINT `fkPCSpeciesID` FOREIGN KEY (`SpeciesID`) REFERENCES `dndspecies` (`SpeciesID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCSubspeciesID` FOREIGN KEY (`SubSpeciesID`) REFERENCES `dndspeciessub` (`SubSpeciesID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkbioPCID` FOREIGN KEY (`PCID`) REFERENCES `pc` (`PCID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pc_class`
--
ALTER TABLE `pc_class`
  ADD CONSTRAINT `fkClassPCID` FOREIGN KEY (`PCID`) REFERENCES `pc` (`PCID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCdndclass1` FOREIGN KEY (`Class1`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCdndclass2` FOREIGN KEY (`Class2`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCdndclass3` FOREIGN KEY (`Class3`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCsubclass1` FOREIGN KEY (`SubClass1`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCsubclass2` FOREIGN KEY (`SubClass2`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPCsubclass3` FOREIGN KEY (`SubClass3`) REFERENCES `dndclass` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pc_stat`
--
ALTER TABLE `pc_stat`
  ADD CONSTRAINT `fkStatPCID` FOREIGN KEY (`PCID`) REFERENCES `pc` (`PCID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_building`
--
ALTER TABLE `quest_building`
  ADD CONSTRAINT `fkBuildingQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkQuestBuildingID` FOREIGN KEY (`BuildingID`) REFERENCES `location_building` (`BuildingID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_environment`
--
ALTER TABLE `quest_environment`
  ADD CONSTRAINT `fkEnvironmentQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkQuestEnvironmentID` FOREIGN KEY (`EnviromentID`) REFERENCES `environmenttypes` (`EnvironmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_mission`
--
ALTER TABLE `quest_mission`
  ADD CONSTRAINT `fkMissionQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkQuestMissionID` FOREIGN KEY (`MissionID`) REFERENCES `mission` (`MissionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_npc`
--
ALTER TABLE `quest_npc`
  ADD CONSTRAINT `fkNPCQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkQuestNPCID` FOREIGN KEY (`NPCID`) REFERENCES `npc` (`NPCID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_region`
--
ALTER TABLE `quest_region`
  ADD CONSTRAINT `fkQuestRegionID` FOREIGN KEY (`RegionID`) REFERENCES `location_region` (`RegionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkRegionQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_rewards`
--
ALTER TABLE `quest_rewards`
  ADD CONSTRAINT `fkRewardQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_settlement`
--
ALTER TABLE `quest_settlement`
  ADD CONSTRAINT `QuestIDSettlementFK` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkQuestSettlementID` FOREIGN KEY (`SettlementID`) REFERENCES `location_settlement` (`SettlementID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_theme`
--
ALTER TABLE `quest_theme`
  ADD CONSTRAINT `fkQuestThemeID` FOREIGN KEY (`ThemeID`) REFERENCES `theme` (`ThemeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkThemeQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quest_warning`
--
ALTER TABLE `quest_warning`
  ADD CONSTRAINT `fkQuestWarningID` FOREIGN KEY (`WarningID`) REFERENCES `warning` (`WarningID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkWarningQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `relationship`
--
ALTER TABLE `relationship`
  ADD CONSTRAINT `fkBubbleID` FOREIGN KEY (`BubbleID`) REFERENCES `relationship_bubble` (`BubbleID`);

--
-- Constraints for table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `fkSessionQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkSessionUserID` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `session_feedback_dm`
--
ALTER TABLE `session_feedback_dm`
  ADD CONSTRAINT `fkSessionDMDMID` FOREIGN KEY (`DMID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `fkSessionDMSessionID` FOREIGN KEY (`SessionID`) REFERENCES `session` (`SessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `session_feedback_module`
--
ALTER TABLE `session_feedback_module`
  ADD CONSTRAINT `fkModuleQuestID` FOREIGN KEY (`QuestID`) REFERENCES `quest` (`QuestID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkModuleSessionID` FOREIGN KEY (`SessionID`) REFERENCES `session` (`SessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `session_feedback_player`
--
ALTER TABLE `session_feedback_player`
  ADD CONSTRAINT `fkPlayerPartyID` FOREIGN KEY (`PartyID`) REFERENCES `party` (`PartyID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkPlayerPartyMemberID` FOREIGN KEY (`PartyID`) REFERENCES `party_member` (`PartyID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `session_reward`
--
ALTER TABLE `session_reward`
  ADD CONSTRAINT `fkRewardPCID` FOREIGN KEY (`PCID`) REFERENCES `pc` (`PCID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkRewardsSesionsID` FOREIGN KEY (`SessionID`) REFERENCES `session` (`SessionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkSessionRewardType` FOREIGN KEY (`RewardTypeID`) REFERENCES `rewardtypes` (`TypeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `username`
--
ALTER TABLE `username`
  ADD CONSTRAINT `fkNameUserID` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_consent`
--
ALTER TABLE `user_consent`
  ADD CONSTRAINT `fkConsentUserID` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkConsentWarningID` FOREIGN KEY (`WarningID`) REFERENCES `warning` (`WarningID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
