-- ============================================================
-- Wild Rift Meta Tracker - Seed Data
-- Patch 7.1h  •  85 Champions  •  Realistic stats & counters
-- ============================================================



-- ============================================================
-- CHAMPIONS
-- Roles: baron, jungle, mid, dragon, support
-- Tiers: S+ (few), S (more), A (many), B (many), C (some), D (rare)
-- ============================================================

INSERT INTO champions (name, slug, title, role, image_url, patch, tier) VALUES
-- ===================== BARON LANE (20) =====================
('Darius',    'darius',    'The Hand of Noxus',        'baron', '/img/champions/darius.webp',    '7.1h', 'S'),
('Garen',     'garen',     'The Might of Demacia',     'baron', '/img/champions/garen.webp',     '7.1h', 'A'),
('Fiora',     'fiora',     'The Grand Duelist',         'baron', '/img/champions/fiora.webp',     '7.1h', 'S+'),
('Camille',   'camille',   'The Steel Shadow',          'baron', '/img/champions/camille.webp',   '7.1h', 'S'),
('Renekton',  'renekton',  'The Butcher of the Sands',  'baron', '/img/champions/renekton.webp',  '7.1h', 'A'),
('Jax',       'jax',       'Grandmaster at Arms',       'baron', '/img/champions/jax.webp',       '7.1h', 'S'),
('Sett',      'sett',      'The Boss',                  'baron', '/img/champions/sett.webp',      '7.1h', 'A'),
('Gragas',    'gragas',    'The Rabble Rouser',         'baron', '/img/champions/gragas.webp',    '7.1h', 'B'),
('Sion',      'sion',      'The Undead Juggernaut',     'baron', '/img/champions/sion.webp',      '7.1h', 'B'),
('Irelia',    'irelia',    'The Blade Dancer',          'baron', '/img/champions/irelia.webp',    '7.1h', 'S'),
('Riven',     'riven',     'The Exile',                 'baron', '/img/champions/riven.webp',     '7.1h', 'A'),
('Jayce',     'jayce',     'The Defender of Tomorrow',  'baron', '/img/champions/jayce.webp',     '7.1h', 'B'),
('Akali',     'akali',     'The Rogue Assassin',        'baron', '/img/champions/akali.webp',     '7.1h', 'S'),
('Kennen',    'kennen',    'The Heart of the Tempest',  'baron', '/img/champions/kennen.webp',    '7.1h', 'B'),
('Malphite',  'malphite',  'Shard of the Monolith',     'baron', '/img/champions/malphite.webp',  '7.1h', 'A'),
('Dr. Mundo', 'dr-mundo',  'The Madman of Zaun',        'baron', '/img/champions/dr-mundo.webp',  '7.1h', 'B'),
('Teemo',     'teemo',     'The Swift Scout',           'baron', '/img/champions/teemo.webp',     '7.1h', 'C'),
('Singed',    'singed',    'The Mad Chemist',           'baron', '/img/champions/singed.webp',    '7.1h', 'C'),
('Wukong',    'wukong',    'The Monkey King',           'baron', '/img/champions/wukong.webp',    '7.1h', 'A'),
('Ambessa',   'ambessa',   'The Matriarch of War',      'baron', '/img/champions/ambessa.webp',   '7.1h', 'S'),

-- ===================== JUNGLE (16) =====================
('Lee Sin',   'lee-sin',   'The Blind Monk',            'jungle', '/img/champions/lee-sin.webp',   '7.1h', 'S+'),
('Vi',        'vi',        'The Piltover Enforcer',     'jungle', '/img/champions/vi.webp',        '7.1h', 'A'),
('Amumu',     'amumu',     'The Sad Mummy',             'jungle', '/img/champions/amumu.webp',     '7.1h', 'A'),
('Evelynn',   'evelynn',   'Agony''s Embrace',          'jungle', '/img/champions/evelynn.webp',   '7.1h', 'S'),
('Kha''Zix',  'khazix',    'The Voidreaver',            'jungle', '/img/champions/khazix.webp',    '7.1h', 'S'),
('Shyvana',   'shyvana',   'The Half-Dragon',           'jungle', '/img/champions/shyvana.webp',   '7.1h', 'B'),
('Xin Zhao',  'xin-zhao',  'The Seneschal of Demacia',  'jungle', '/img/champions/xin-zhao.webp',  '7.1h', 'A'),
('Jarvan IV', 'jarvan-iv', 'The Exemplar of Demacia',   'jungle', '/img/champions/jarvan-iv.webp', '7.1h', 'A'),
('Graves',    'graves',    'The Outlaw',                'jungle', '/img/champions/graves.webp',    '7.1h', 'S'),
('Olaf',      'olaf',      'The Berserker',             'jungle', '/img/champions/olaf.webp',      '7.1h', 'B'),
('Rammus',    'rammus',    'The Armordillo',            'jungle', '/img/champions/rammus.webp',    '7.1h', 'B'),
('Nunu',      'nunu',      'The Boy and His Yeti',      'jungle', '/img/champions/nunu.webp',      '7.1h', 'B'),
('Morgana',   'morgana',   'The Fallen',                'jungle', '/img/champions/morgana.webp',   '7.1h', 'C'),
('Nautilus',  'nautilus',  'The Titan of the Depths',   'jungle', '/img/champions/nautilus.webp',  '7.1h', 'C'),
('Ekko',      'ekko',      'The Boy Who Shattered Time','jungle', '/img/champions/ekko.webp',      '7.1h', 'A'),
('Lillia',    'lillia',    'The Bashful Bloom',         'jungle', '/img/champions/lillia.webp',    '7.1h', 'A'),

-- ===================== MID LANE (19) =====================
('Ahri',        'ahri',        'The Nine-Tailed Fox',        'mid', '/img/champions/ahri.webp',        '7.1h', 'S'),
('Zed',         'zed',         'The Master of Shadows',      'mid', '/img/champions/zed.webp',         '7.1h', 'S'),
('Yasuo',       'yasuo',       'The Unforgiven',             'mid', '/img/champions/yasuo.webp',       '7.1h', 'A'),
('Orianna',     'orianna',     'The Lady of Clockwork',      'mid', '/img/champions/orianna.webp',     '7.1h', 'A'),
('Lux',         'lux',         'The Lady of Luminosity',     'mid', '/img/champions/lux.webp',         '7.1h', 'A'),
('Veigar',      'veigar',      'The Tiny Master of Evil',    'mid', '/img/champions/veigar.webp',      '7.1h', 'B'),
('Katarina',    'katarina',    'The Sinister Blade',         'mid', '/img/champions/katarina.webp',    '7.1h', 'S'),
('Fizz',        'fizz',        'The Tidal Trickster',        'mid', '/img/champions/fizz.webp',        '7.1h', 'A'),
('Twisted Fate','twisted-fate','The Card Master',            'mid', '/img/champions/twisted-fate.webp','7.1h', 'B'),
('Diana',       'diana',       'Scorn of the Moon',          'mid', '/img/champions/diana.webp',       '7.1h', 'S'),
('Ziggs',       'ziggs',       'The Hexplosives Expert',     'mid', '/img/champions/ziggs.webp',       '7.1h', 'B'),
('Brand',       'brand',       'The Burning Vengeance',      'mid', '/img/champions/brand.webp',       '7.1h', 'B'),
('Annie',       'annie',       'The Dark Child',             'mid', '/img/champions/annie.webp',       '7.1h', 'C'),
('Galio',       'galio',       'The Colossus',               'mid', '/img/champions/galio.webp',       '7.1h', 'A'),
('Corki',       'corki',       'The Daring Bombardier',      'mid', '/img/champions/corki.webp',       '7.1h', 'B'),
('Akshan',      'akshan',      'The Rogue Sentinel',         'mid', '/img/champions/akshan.webp',      '7.1h', 'A'),
('Yone',        'yone',        'The Unforgotten',            'mid', '/img/champions/yone.webp',        '7.1h', 'S+'),
('Kassadin',    'kassadin',    'The Void Walker',            'mid', '/img/champions/kassadin.webp',    '7.1h', 'B'),
('Vex',         'vex',         'The Gloomist',               'mid', '/img/champions/vex.webp',         '7.1h', 'A'),

-- ===================== DRAGON LANE / ADC (15) =====================
('Jinx',         'jinx',         'The Loose Cannon',       'dragon', '/img/champions/jinx.webp',         '7.1h', 'S'),
('Kai''Sa',      'kaisa',        'Daughter of the Void',   'dragon', '/img/champions/kaisa.webp',        '7.1h', 'S+'),
('Jhin',         'jhin',         'The Virtuoso',           'dragon', '/img/champions/jhin.webp',         '7.1h', 'S'),
('Ezreal',       'ezreal',       'The Prodigal Explorer',  'dragon', '/img/champions/ezreal.webp',       '7.1h', 'A'),
('Vayne',        'vayne',        'The Night Hunter',       'dragon', '/img/champions/vayne.webp',        '7.1h', 'S'),
('Miss Fortune', 'miss-fortune', 'The Bounty Hunter',      'dragon', '/img/champions/miss-fortune.webp', '7.1h', 'A'),
('Caitlyn',      'caitlyn',      'The Sheriff of Piltover','dragon', '/img/champions/caitlyn.webp',      '7.1h', 'A'),
('Draven',       'draven',       'The Glorious Executioner','dragon', '/img/champions/draven.webp',      '7.1h', 'A'),
('Xayah',        'xayah',        'The Rebel',              'dragon', '/img/champions/xayah.webp',        '7.1h', 'B'),
('Lucian',       'lucian',       'The Purifier',           'dragon', '/img/champions/lucian.webp',       '7.1h', 'A'),
('Tristana',     'tristana',     'The Yordle Gunner',      'dragon', '/img/champions/tristana.webp',     '7.1h', 'B'),
('Varus',        'varus',        'The Arrow of Retribution','dragon', '/img/champions/varus.webp',       '7.1h', 'B'),
('Ashe',         'ashe',         'The Frost Archer',       'dragon', '/img/champions/ashe.webp',         '7.1h', 'B'),
('Samira',       'samira',       'The Desert Rose',        'dragon', '/img/champions/samira.webp',       '7.1h', 'S'),
('Nilah',        'nilah',        'The Joy Unbound',        'dragon', '/img/champions/nilah.webp',        '7.1h', 'A'),

-- ===================== SUPPORT (15) =====================
('Thresh',      'thresh',      'The Chain Warden',       'support', '/img/champions/thresh.webp',      '7.1h', 'S+'),
('Lulu',        'lulu',        'The Fae Sorceress',      'support', '/img/champions/lulu.webp',        '7.1h', 'S'),
('Nami',        'nami',        'The Tidecaller',         'support', '/img/champions/nami.webp',        '7.1h', 'S'),
('Seraphine',   'seraphine',   'The Starry-Eyed Songstress','support', '/img/champions/seraphine.webp','7.1h', 'A'),
('Senna',       'senna',       'The Redeemer',           'support', '/img/champions/senna.webp',       '7.1h', 'A'),
('Braum',       'braum',       'The Heart of the Freljord','support', '/img/champions/braum.webp',     '7.1h', 'A'),
('Leona',       'leona',       'The Radiant Dawn',       'support', '/img/champions/leona.webp',       '7.1h', 'S'),
('Alistar',     'alistar',     'The Minotaur',           'support', '/img/champions/alistar.webp',     '7.1h', 'B'),
('Rakan',       'rakan',       'The Charmer',            'support', '/img/champions/rakan.webp',       '7.1h', 'A'),
('Karma',       'karma',       'The Enlightened One',    'support', '/img/champions/karma.webp',       '7.1h', 'B'),
('Janna',       'janna',       'The Storm''s Fury',      'support', '/img/champions/janna.webp',       '7.1h', 'A'),
('Soraka',      'soraka',      'The Starchild',          'support', '/img/champions/soraka.webp',      '7.1h', 'B'),
('Pyke',        'pyke',        'The Bloodharbor Ripper', 'support', '/img/champions/pyke.webp',        '7.1h', 'A'),
('Yuumi',       'yuumi',       'The Magical Cat',        'support', '/img/champions/yuumi.webp',       '7.1h', 'C'),
('Blitzcrank',  'blitzcrank',  'The Great Steam Golem',  'support', '/img/champions/blitzcrank.webp',  '7.1h', 'B');

-- ============================================================
-- STATISTICS
-- Realistic win/pick/ban rates for patch 7.1h
-- ============================================================

-- Helper: We reference champion IDs by subquery on name for portability.

-- ===== BARON LANE STATS =====
INSERT INTO statistics (champion_id, role, win_rate, pick_rate, ban_rate, tier, patch) VALUES
((SELECT id FROM champions WHERE slug='darius'),    'baron', 52.40, 12.30, 18.50, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='garen'),     'baron', 51.80,  8.50,  3.20, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'),     'baron', 53.10, 14.80, 25.40, 'S+', '7.1h'),
((SELECT id FROM champions WHERE slug='camille'),   'baron', 52.60, 11.20, 15.30, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='renekton'),  'baron', 51.20,  7.90,  5.10, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='jax'),       'baron', 52.90, 10.50, 14.20, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='sett'),      'baron', 51.50,  9.10,  6.80, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='gragas'),    'baron', 50.10,  5.40,  2.10, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='sion'),      'baron', 49.80,  4.20,  1.50, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'),    'baron', 52.30, 13.60, 20.10, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='riven'),     'baron', 51.40,  8.80,  7.90, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='jayce'),     'baron', 49.50,  4.80,  2.50, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='akali'),     'baron', 52.10, 11.70, 22.30, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='kennen'),    'baron', 49.90,  3.90,  1.80, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='malphite'),  'baron', 51.60,  7.20,  4.60, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='dr-mundo'),  'baron', 50.30,  5.10,  2.30, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='teemo'),     'baron', 48.20,  3.50,  5.60, 'C',  '7.1h'),
((SELECT id FROM champions WHERE slug='singed'),    'baron', 47.90,  2.10,  0.80, 'C',  '7.1h'),
((SELECT id FROM champions WHERE slug='wukong'),    'baron', 51.30,  6.80,  4.20, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'),   'baron', 53.00, 15.20, 28.10, 'S',  '7.1h');

-- ===== JUNGLE STATS =====
INSERT INTO statistics (champion_id, role, win_rate, pick_rate, ban_rate, tier, patch) VALUES
((SELECT id FROM champions WHERE slug='lee-sin'),   'jungle', 53.50, 18.90, 30.20, 'S+', '7.1h'),
((SELECT id FROM champions WHERE slug='vi'),        'jungle', 51.80,  7.60,  4.30, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='amumu'),     'jungle', 51.50,  6.90,  3.80, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'),   'jungle', 52.80, 10.40, 16.50, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'),    'jungle', 52.60, 11.80, 19.70, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='shyvana'),   'jungle', 50.10,  4.50,  1.90, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='xin-zhao'),  'jungle', 51.20,  6.30,  3.50, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='jarvan-iv'), 'jungle', 51.40,  7.10,  4.10, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='graves'),    'jungle', 52.50, 12.30, 15.80, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='olaf'),      'jungle', 50.40,  5.20,  2.40, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='rammus'),    'jungle', 50.80,  4.80,  3.10, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='nunu'),      'jungle', 49.90,  4.10,  1.70, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='morgana'),   'jungle', 48.50,  2.80,  1.20, 'C',  '7.1h'),
((SELECT id FROM champions WHERE slug='nautilus'),  'jungle', 48.10,  2.30,  0.90, 'C',  '7.1h'),
((SELECT id FROM champions WHERE slug='ekko'),      'jungle', 51.70,  8.40,  7.60, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='lillia'),    'jungle', 51.30,  6.50,  5.20, 'A',  '7.1h');

-- ===== MID LANE STATS =====
INSERT INTO statistics (champion_id, role, win_rate, pick_rate, ban_rate, tier, patch) VALUES
((SELECT id FROM champions WHERE slug='ahri'),         'mid', 52.40, 13.20, 12.80, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='zed'),          'mid', 52.70, 15.60, 35.40, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'),        'mid', 51.10, 14.30, 22.50, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='orianna'),      'mid', 51.30,  7.80,  3.40, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='lux'),          'mid', 51.60,  9.40,  4.80, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='veigar'),       'mid', 50.20,  5.90,  6.30, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'),     'mid', 53.20, 12.10, 28.90, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='fizz'),         'mid', 51.90,  8.60,  9.40, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='twisted-fate'), 'mid', 50.40,  5.30,  2.70, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='diana'),        'mid', 52.60, 10.80, 14.20, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='ziggs'),        'mid', 49.80,  4.60,  1.90, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='brand'),        'mid', 50.50,  5.10,  3.60, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='annie'),        'mid', 48.90,  3.20,  1.10, 'C',  '7.1h'),
((SELECT id FROM champions WHERE slug='galio'),        'mid', 51.40,  6.70,  4.50, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='corki'),        'mid', 49.60,  3.80,  1.50, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='akshan'),       'mid', 51.50,  7.30,  5.80, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='yone'),         'mid', 54.10, 16.20, 38.50, 'S+', '7.1h'),
((SELECT id FROM champions WHERE slug='kassadin'),     'mid', 50.10,  4.40,  3.90, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='vex'),          'mid', 51.80,  7.50,  6.10, 'A',  '7.1h');

-- ===== DRAGON LANE STATS =====
INSERT INTO statistics (champion_id, role, win_rate, pick_rate, ban_rate, tier, patch) VALUES
((SELECT id FROM champions WHERE slug='jinx'),         'dragon', 52.30, 14.50, 10.20, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'),        'dragon', 53.80, 20.10, 22.40, 'S+', '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'),         'dragon', 52.50, 15.30, 11.60, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='ezreal'),       'dragon', 51.20, 12.80,  5.90, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'),        'dragon', 52.90, 13.40, 18.30, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='miss-fortune'), 'dragon', 51.40,  9.70,  4.80, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='caitlyn'),      'dragon', 51.60, 10.20,  5.30, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='draven'),       'dragon', 51.80,  7.60,  8.90, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='xayah'),        'dragon', 50.30,  5.40,  2.80, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='lucian'),       'dragon', 51.30,  8.90,  5.10, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='tristana'),     'dragon', 50.10,  4.80,  2.10, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='varus'),        'dragon', 49.80,  4.20,  1.80, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='ashe'),         'dragon', 50.40,  6.30,  2.50, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='samira'),       'dragon', 52.70, 11.50, 19.60, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='nilah'),        'dragon', 51.50,  6.80,  7.40, 'A',  '7.1h');

-- ===== SUPPORT STATS =====
INSERT INTO statistics (champion_id, role, win_rate, pick_rate, ban_rate, tier, patch) VALUES
((SELECT id FROM champions WHERE slug='thresh'),      'support', 53.60, 18.40, 15.80, 'S+', '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'),        'support', 52.80, 14.20, 10.30, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='nami'),        'support', 52.50, 12.60,  8.40, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='seraphine'),   'support', 51.40,  9.30,  5.60, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='senna'),       'support', 51.20,  8.80,  6.20, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='braum'),       'support', 51.60,  7.40,  3.90, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='leona'),       'support', 52.90, 13.10, 12.50, 'S',  '7.1h'),
((SELECT id FROM champions WHERE slug='alistar'),     'support', 50.30,  5.20,  2.80, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='rakan'),       'support', 51.80,  8.10,  6.70, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='karma'),       'support', 49.90,  4.60,  2.10, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='janna'),       'support', 51.50,  7.90,  3.50, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='soraka'),      'support', 50.60,  5.80,  4.30, 'B',  '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'),        'support', 51.70,  9.50, 11.80, 'A',  '7.1h'),
((SELECT id FROM champions WHERE slug='yuumi'),       'support', 47.80,  3.40,  8.60, 'C',  '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'),  'support', 50.40,  6.10,  9.30, 'B',  '7.1h');

-- ============================================================
-- COUNTERS
-- Each popular champion gets 4-5 strong_against + 4-5 weak_against
-- win_rate_diff: positive = favourable spread for the champion_id
-- ============================================================

-- Helper macro-style: champion_id is the "perspective" champion

-- ─── DARIUS ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='garen'),    'strong_against',  4.20, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='sion'),     'strong_against',  5.80, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='dr-mundo'), 'strong_against',  3.90, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='malphite'), 'strong_against',  3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='teemo'),    'strong_against',  2.50, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='fiora'),    'weak_against',   -4.60, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='camille'),  'weak_against',   -3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='jax'),      'weak_against',   -3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='irelia'),   'weak_against',   -2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='darius'), (SELECT id FROM champions WHERE slug='kennen'),   'weak_against',   -2.10, '7.1h');

-- ─── FIORA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='darius'),    'strong_against',  4.60, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='garen'),     'strong_against',  5.20, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='sett'),      'strong_against',  3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='malphite'),  'strong_against',  4.10, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='dr-mundo'),  'strong_against',  5.50, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='renekton'),  'weak_against',   -3.20, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='akali'),     'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='kennen'),    'weak_against',   -3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='fiora'), (SELECT id FROM champions WHERE slug='jayce'),     'weak_against',   -2.40, '7.1h');

-- ─── CAMILLE ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='darius'),   'strong_against',  3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='sion'),     'strong_against',  4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='dr-mundo'), 'strong_against',  3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='gragas'),   'strong_against',  2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='jax'),      'weak_against',   -4.10, '7.1h'),
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='fiora'),    'weak_against',   -3.70, '7.1h'),
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='renekton'), 'weak_against',   -3.30, '7.1h'),
((SELECT id FROM champions WHERE slug='camille'), (SELECT id FROM champions WHERE slug='riven'),    'weak_against',   -2.60, '7.1h');

-- ─── JAX ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='camille'),  'strong_against',  4.10, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='darius'),   'strong_against',  3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='garen'),    'strong_against',  3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='sett'),     'strong_against',  2.70, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='irelia'),   'strong_against',  2.30, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='fiora'),    'weak_against',   -2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='akali'),    'weak_against',   -3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='kennen'),   'weak_against',   -4.20, '7.1h'),
((SELECT id FROM champions WHERE slug='jax'), (SELECT id FROM champions WHERE slug='jayce'),    'weak_against',   -3.10, '7.1h');

-- ─── IRELIA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='darius'),   'strong_against',  2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='garen'),    'strong_against',  4.50, '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='sion'),     'strong_against',  5.10, '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='malphite'), 'strong_against',  2.60, '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='renekton'), 'weak_against',   -3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='sett'),     'weak_against',   -2.50, '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='jax'),      'weak_against',   -2.30, '7.1h'),
((SELECT id FROM champions WHERE slug='irelia'), (SELECT id FROM champions WHERE slug='fiora'),    'weak_against',   -3.10, '7.1h');

-- ─── AMBESSA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='garen'),     'strong_against',  5.10, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='sion'),      'strong_against',  6.20, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='dr-mundo'),  'strong_against',  4.30, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='malphite'),  'strong_against',  3.70, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='teemo'),     'strong_against',  5.80, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='fiora'),     'weak_against',   -3.90, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='jax'),       'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='camille'),   'weak_against',   -2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='ambessa'), (SELECT id FROM champions WHERE slug='kennen'),    'weak_against',   -3.60, '7.1h');

-- ─── LEE SIN ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='amumu'),    'strong_against',  4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='shyvana'),  'strong_against',  5.20, '7.1h'),
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='nunu'),     'strong_against',  3.90, '7.1h'),
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='evelynn'),  'strong_against',  3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='olaf'),     'weak_against',   -2.60, '7.1h'),
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='rammus'),   'weak_against',   -3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='vi'),       'weak_against',   -2.10, '7.1h'),
((SELECT id FROM champions WHERE slug='lee-sin'), (SELECT id FROM champions WHERE slug='graves'),   'weak_against',   -1.80, '7.1h');

-- ─── EVELYNN ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='shyvana'),    'strong_against',  4.30, '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='amumu'),      'strong_against',  3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='nunu'),       'strong_against',  4.10, '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='nautilus'),   'strong_against',  5.50, '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='lee-sin'),    'weak_against',   -3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='khazix'),     'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='graves'),     'weak_against',   -3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='evelynn'), (SELECT id FROM champions WHERE slug='xin-zhao'),   'weak_against',   -2.40, '7.1h');

-- ─── KHA'ZIX ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='evelynn'),   'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='ekko'),      'strong_against',  3.20, '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='lillia'),    'strong_against',  4.10, '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='nunu'),      'strong_against',  3.70, '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='rammus'),    'weak_against',   -5.30, '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='vi'),        'weak_against',   -2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='jarvan-iv'), 'weak_against',   -2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='khazix'), (SELECT id FROM champions WHERE slug='lee-sin'),   'weak_against',   -1.90, '7.1h');

-- ─── GRAVES ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='lee-sin'),  'strong_against',  1.80, '7.1h'),
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='evelynn'),  'strong_against',  3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='ekko'),     'strong_against',  2.60, '7.1h'),
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='shyvana'),  'strong_against',  4.40, '7.1h'),
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='rammus'),   'weak_against',   -4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='olaf'),     'weak_against',   -3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='xin-zhao'), 'weak_against',   -2.70, '7.1h'),
((SELECT id FROM champions WHERE slug='graves'), (SELECT id FROM champions WHERE slug='vi'),       'weak_against',   -2.20, '7.1h');

-- ─── AHRI ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='veigar'),       'strong_against',  3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='twisted-fate'), 'strong_against',  4.20, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='ziggs'),        'strong_against',  3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='brand'),        'strong_against',  2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='annie'),        'strong_against',  4.60, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='zed'),          'weak_against',   -3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='katarina'),     'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='fizz'),         'weak_against',   -3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='ahri'), (SELECT id FROM champions WHERE slug='diana'),        'weak_against',   -2.50, '7.1h');

-- ─── ZED ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='ahri'),     'strong_against',  3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='lux'),      'strong_against',  4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='orianna'),  'strong_against',  3.20, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='veigar'),   'strong_against',  5.10, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='ziggs'),    'strong_against',  4.50, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='galio'),    'weak_against',   -5.30, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='diana'),    'weak_against',   -3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='yasuo'),    'weak_against',   -2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='zed'), (SELECT id FROM champions WHERE slug='vex'),      'weak_against',   -3.90, '7.1h');

-- ─── KATARINA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='ahri'),     'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='lux'),      'strong_against',  4.30, '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='ziggs'),    'strong_against',  5.20, '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='brand'),    'strong_against',  3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='kassadin'), 'weak_against',   -4.70, '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='galio'),    'weak_against',   -5.10, '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='diana'),    'weak_against',   -3.20, '7.1h'),
((SELECT id FROM champions WHERE slug='katarina'), (SELECT id FROM champions WHERE slug='vex'),      'weak_against',   -4.40, '7.1h');

-- ─── YONE ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='ahri'),     'strong_against',  3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='lux'),      'strong_against',  4.90, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='orianna'),  'strong_against',  3.30, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='veigar'),   'strong_against',  5.40, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='corki'),    'strong_against',  4.10, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='vex'),      'weak_against',   -4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='diana'),    'weak_against',   -3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='galio'),    'weak_against',   -4.20, '7.1h'),
((SELECT id FROM champions WHERE slug='yone'), (SELECT id FROM champions WHERE slug='kassadin'), 'weak_against',   -3.10, '7.1h');

-- ─── DIANA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='zed'),      'strong_against',  3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='katarina'), 'strong_against',  3.20, '7.1h'),
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='yone'),     'strong_against',  3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='yasuo'),    'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='kassadin'), 'weak_against',   -4.30, '7.1h'),
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='galio'),    'weak_against',   -3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='vex'),      'weak_against',   -2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='diana'), (SELECT id FROM champions WHERE slug='orianna'),  'weak_against',   -2.10, '7.1h');

-- ─── KAI'SA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='ashe'),         'strong_against',  4.20, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='varus'),        'strong_against',  3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='miss-fortune'), 'strong_against',  3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='xayah'),        'strong_against',  2.70, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='tristana'),     'strong_against',  3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='draven'),       'weak_against',   -3.90, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='caitlyn'),      'weak_against',   -2.60, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='jinx'),         'weak_against',   -1.80, '7.1h'),
((SELECT id FROM champions WHERE slug='kaisa'), (SELECT id FROM champions WHERE slug='samira'),       'weak_against',   -2.30, '7.1h');

-- ─── JINX ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='kaisa'),        'strong_against',  1.80, '7.1h'),
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='ashe'),         'strong_against',  3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='varus'),        'strong_against',  4.10, '7.1h'),
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='tristana'),     'strong_against',  3.20, '7.1h'),
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='draven'),       'weak_against',   -4.50, '7.1h'),
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='samira'),       'weak_against',   -3.30, '7.1h'),
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='lucian'),       'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='jinx'), (SELECT id FROM champions WHERE slug='vayne'),        'weak_against',   -1.90, '7.1h');

-- ─── VAYNE ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='jinx'),         'strong_against',  1.90, '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='ashe'),         'strong_against',  4.30, '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='xayah'),        'strong_against',  3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='miss-fortune'), 'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='draven'),       'weak_against',   -5.10, '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='caitlyn'),      'weak_against',   -3.70, '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='lucian'),       'weak_against',   -2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='vayne'), (SELECT id FROM champions WHERE slug='jhin'),         'weak_against',   -2.10, '7.1h');

-- ─── SAMIRA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='jinx'),     'strong_against',  3.30, '7.1h'),
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='kaisa'),    'strong_against',  2.30, '7.1h'),
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='ashe'),     'strong_against',  4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='xayah'),    'strong_against',  3.90, '7.1h'),
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='caitlyn'),  'weak_against',   -4.20, '7.1h'),
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='vayne'),    'weak_against',   -2.60, '7.1h'),
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='ezreal'),   'weak_against',   -3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='samira'), (SELECT id FROM champions WHERE slug='jhin'),     'weak_against',   -1.80, '7.1h');

-- ─── THRESH ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='soraka'),     'strong_against',  4.50, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='yuumi'),      'strong_against',  6.20, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='senna'),      'strong_against',  3.80, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='karma'),      'strong_against',  3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='janna'),      'strong_against',  2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='leona'),      'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='alistar'),    'weak_against',   -3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='braum'),      'weak_against',   -2.30, '7.1h'),
((SELECT id FROM champions WHERE slug='thresh'), (SELECT id FROM champions WHERE slug='blitzcrank'), 'weak_against',   -1.70, '7.1h');

-- ─── LEONA ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='thresh'),    'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='yuumi'),     'strong_against',  5.90, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='senna'),     'strong_against',  4.20, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='soraka'),    'strong_against',  4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='karma'),     'strong_against',  3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='nami'),      'weak_against',   -2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='lulu'),      'weak_against',   -3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='janna'),     'weak_against',   -2.70, '7.1h'),
((SELECT id FROM champions WHERE slug='leona'), (SELECT id FROM champions WHERE slug='rakan'),     'weak_against',   -1.90, '7.1h');

-- ─── LULU ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='leona'),      'strong_against',  3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='alistar'),    'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='blitzcrank'), 'strong_against',  3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='pyke'),       'strong_against',  2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='nami'),       'weak_against',   -1.80, '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='soraka'),     'weak_against',   -2.20, '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='senna'),      'weak_against',   -1.60, '7.1h'),
((SELECT id FROM champions WHERE slug='lulu'), (SELECT id FROM champions WHERE slug='karma'),      'weak_against',   -1.40, '7.1h');

-- ─── NAMI ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='leona'),   'strong_against',  2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='lulu'),    'strong_against',  1.80, '7.1h'),
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='rakan'),   'strong_against',  2.10, '7.1h'),
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='alistar'), 'strong_against',  3.30, '7.1h'),
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='thresh'),  'weak_against',   -2.50, '7.1h'),
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='pyke'),    'weak_against',   -3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='braum'),   'weak_against',   -2.00, '7.1h'),
((SELECT id FROM champions WHERE slug='nami'), (SELECT id FROM champions WHERE slug='blitzcrank'), 'weak_against', -3.80, '7.1h');

-- ─── PYKE ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='nami'),       'strong_against',  3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='soraka'),     'strong_against',  4.60, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='yuumi'),      'strong_against',  5.30, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='seraphine'),  'strong_against',  3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='karma'),      'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='lulu'),       'weak_against',   -2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='leona'),      'weak_against',   -3.70, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='braum'),      'weak_against',   -3.20, '7.1h'),
((SELECT id FROM champions WHERE slug='pyke'), (SELECT id FROM champions WHERE slug='thresh'),     'weak_against',   -2.10, '7.1h');

-- ─── YASUO ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='zed'),      'strong_against',  2.40, '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='lux'),      'strong_against',  3.70, '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='ziggs'),    'strong_against',  4.80, '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='veigar'),   'strong_against',  3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='diana'),    'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='vex'),      'weak_against',   -4.50, '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='galio'),    'weak_against',   -3.90, '7.1h'),
((SELECT id FROM champions WHERE slug='yasuo'), (SELECT id FROM champions WHERE slug='fizz'),     'weak_against',   -2.30, '7.1h');

-- ─── JHIN ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='vayne'),        'strong_against',  2.10, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='ashe'),         'strong_against',  3.40, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='xayah'),        'strong_against',  2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='miss-fortune'), 'strong_against',  1.90, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='samira'),       'strong_against',  1.80, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='draven'),       'weak_against',   -3.60, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='lucian'),       'weak_against',   -2.70, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='kaisa'),        'weak_against',   -1.50, '7.1h'),
((SELECT id FROM champions WHERE slug='jhin'), (SELECT id FROM champions WHERE slug='ezreal'),       'weak_against',   -2.20, '7.1h');

-- ─── BLITZCRANK ───
INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch) VALUES
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='yuumi'),     'strong_against',  5.80, '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='soraka'),    'strong_against',  4.30, '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='senna'),     'strong_against',  3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='karma'),     'strong_against',  2.90, '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='lulu'),      'weak_against',   -3.50, '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='leona'),     'weak_against',   -2.80, '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='braum'),     'weak_against',   -3.10, '7.1h'),
((SELECT id FROM champions WHERE slug='blitzcrank'), (SELECT id FROM champions WHERE slug='thresh'),    'weak_against',    1.70, '7.1h');
