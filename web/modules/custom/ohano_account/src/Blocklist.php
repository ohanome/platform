<?php

namespace Drupal\ohano_account;

/**
 * Class which holds all invalid strings a user could use.
 *
 * @package Drupal\ohano_account
 */
class Blocklist {

  const USERNAME = [
    "ohano",
    "ohanome",
    "0hanome",
    "ohan0me",
    "0han0me",
    "oh4nome",
    "0h4nome",
    "oh4n0me",
    "0h4n0me",
    "ohanom3",
    "0hanom3",
    "ohan0m3",
    "0han0m3",
    "oh4nom3",
    "0h4nom3",
    "oh4n0m3",
    "0h4n0m3",
    "ohano.me",
    "0hano.me",
    "ohan0.me",
    "0han0.me",
    "oh4no.me",
    "0h4no.me",
    "oh4n0.me",
    "0h4n0.me",
    "ohano.m3",
    "0hano.m3",
    "ohan0.m3",
    "0han0.m3",
    "oh4no.m3",
    "0h4no.m3",
    "oh4n0.m3",
    "0h4n0.m3",
    "bigcity",
    "bigcitymc",
    "bcmc",
    "git",
    "htaccess",
    "htpasswd",
    "well_known",
    "400",
    "401",
    "403",
    "404",
    "405",
    "406",
    "407",
    "408",
    "409",
    "410",
    "411",
    "412",
    "413",
    "414",
    "415",
    "416",
    "417",
    "421",
    "422",
    "423",
    "424",
    "426",
    "428",
    "429",
    "431",
    "500",
    "501",
    "502",
    "503",
    "504",
    "505",
    "506",
    "507",
    "508",
    "509",
    "510",
    "511",
    "_domainkey",
    "about",
    "about_us",
    "abuse",
    "access",
    "account",
    "accounts",
    "ad",
    "add",
    "admin",
    "administration",
    "administrator",
    "ads",
    "ads.txt",
    "advertise",
    "advertising",
    "aes128_ctr",
    "aes128_gcm",
    "aes192_ctr",
    "aes256_ctr",
    "aes256_gcm",
    "affiliate",
    "affiliates",
    "ajax",
    "alert",
    "alerts",
    "alpha",
    "amp",
    "analytics",
    "api",
    "app",
    "app_ads.txt",
    "apps",
    "asc",
    "assets",
    "atom",
    "auth",
    "authentication",
    "authorize",
    "autoconfig",
    "autodiscover",
    "avatar",
    "backup",
    "banner",
    "banners",
    "bbs",
    "beta",
    "billing",
    "billings",
    "blog",
    "blogs",
    "board",
    "bookmark",
    "bookmarks",
    "broadcasthost",
    "business",
    "buy",
    "cache",
    "calendar",
    "campaign",
    "captcha",
    "careers",
    "cart",
    "cas",
    "categories",
    "category",
    "cdn",
    "cgi",
    "cgi_bin",
    "chacha20_poly1305",
    "change",
    "channel",
    "channels",
    "chart",
    "chat",
    "checkout",
    "clear",
    "client",
    "close",
    "cloud",
    "cms",
    "com",
    "comment",
    "comments",
    "community",
    "compare",
    "compose",
    "config",
    "connect",
    "contact",
    "contest",
    "cookies",
    "copy",
    "copyright",
    "count",
    "cp",
    "cpanel",
    "create",
    "crossdomain.xml",
    "css",
    "curve25519_sha256",
    "customer",
    "customers",
    "customize",
    "dashboard",
    "db",
    "deals",
    "debug",
    "delete",
    "desc",
    "destroy",
    "dev",
    "developer",
    "developers",
    "diffie_hellman_group_exchange_sha256",
    "diffie_hellman_group14_sha1",
    "disconnect",
    "discuss",
    "dns",
    "dns0",
    "dns1",
    "dns2",
    "dns3",
    "dns4",
    "docs",
    "documentation",
    "domain",
    "download",
    "downloads",
    "downvote",
    "draft",
    "drop",
    "ecdh_sha2_nistp256",
    "ecdh_sha2_nistp384",
    "ecdh_sha2_nistp521",
    "edit",
    "editor",
    "email",
    "enterprise",
    "error",
    "errors",
    "event",
    "events",
    "example",
    "exception",
    "exit",
    "explore",
    "export",
    "extensions",
    "false",
    "family",
    "faq",
    "faqs",
    "favicon.ico",
    "features",
    "feed",
    "feedback",
    "feeds",
    "file",
    "files",
    "filter",
    "follow",
    "follower",
    "followers",
    "following",
    "fonts",
    "forgot",
    "forgot_password",
    "forgotpassword",
    "form",
    "forms",
    "forum",
    "forums",
    "friend",
    "friends",
    "ftp",
    "get",
    "git",
    "go",
    "graphql",
    "group",
    "groups",
    "guest",
    "guidelines",
    "guides",
    "head",
    "header",
    "help",
    "hide",
    "hmac_sha",
    "hmac_sha1",
    "hmac_sha1_etm",
    "hmac_sha2_256",
    "hmac_sha2_256_etm",
    "hmac_sha2_512",
    "hmac_sha2_512_etm",
    "home",
    "host",
    "hosting",
    "hostmaster",
    "htpasswd",
    "http",
    "httpd",
    "https",
    "humans.txt",
    "icons",
    "images",
    "imap",
    "img",
    "import",
    "index",
    "info",
    "insert",
    "investors",
    "invitations",
    "invite",
    "invites",
    "invoice",
    "is",
    "isatap",
    "issues",
    "it",
    "jobs",
    "join",
    "js",
    "json",
    "keybase.txt",
    "learn",
    "legal",
    "license",
    "licensing",
    "like",
    "limit",
    "live",
    "load",
    "local",
    "localdomain",
    "localhost",
    "lock",
    "login",
    "logout",
    "lost_password",
    "m",
    "mail",
    "mail0",
    "mail1",
    "mail2",
    "mail3",
    "mail4",
    "mail5",
    "mail6",
    "mail7",
    "mail8",
    "mail9",
    "mailer_daemon",
    "mailerdaemon",
    "map",
    "marketing",
    "marketplace",
    "master",
    "me",
    "media",
    "member",
    "members",
    "message",
    "messages",
    "metrics",
    "mis",
    "mobile",
    "moderator",
    "modify",
    "more",
    "mx",
    "mx1",
    "my",
    "net",
    "network",
    "new",
    "news",
    "newsletter",
    "newsletters",
    "next",
    "nil",
    "no_reply",
    "nobody",
    "noc",
    "none",
    "noreply",
    "notification",
    "notifications",
    "ns",
    "ns0",
    "ns1",
    "ns2",
    "ns3",
    "ns4",
    "ns5",
    "ns6",
    "ns7",
    "ns8",
    "ns9",
    "null",
    "oauth",
    "oauth2",
    "offer",
    "offers",
    "online",
    "openid",
    "order",
    "orders",
    "overview",
    "owa",
    "owner",
    "page",
    "pages",
    "partners",
    "passwd",
    "password",
    "pay",
    "payment",
    "payments",
    "paypal",
    "photo",
    "photos",
    "pixel",
    "plans",
    "plugins",
    "policies",
    "policy",
    "pop",
    "pop3",
    "popular",
    "portal",
    "portfolio",
    "post",
    "postfix",
    "postmaster",
    "poweruser",
    "preferences",
    "premium",
    "press",
    "previous",
    "pricing",
    "print",
    "privacy",
    "privacy_policy",
    "private",
    "prod",
    "product",
    "production",
    "profile",
    "profiles",
    "project",
    "projects",
    "promo",
    "public",
    "purchase",
    "put",
    "quota",
    "redirect",
    "reduce",
    "refund",
    "refunds",
    "register",
    "registration",
    "remove",
    "replies",
    "reply",
    "report",
    "request",
    "request_password",
    "reset",
    "reset_password",
    "response",
    "return",
    "returns",
    "review",
    "reviews",
    "robots.txt",
    "root",
    "rootuser",
    "rsa_sha2_2",
    "rsa_sha2_512",
    "rss",
    "rules",
    "sales",
    "save",
    "script",
    "sdk",
    "search",
    "secure",
    "security",
    "select",
    "services",
    "session",
    "sessions",
    "settings",
    "setup",
    "share",
    "shift",
    "shop",
    "signin",
    "signup",
    "site",
    "sitemap",
    "sites",
    "smtp",
    "sort",
    "source",
    "sql",
    "ssh",
    "ssh_rsa",
    "ssl",
    "ssladmin",
    "ssladministrator",
    "sslwebmaster",
    "stage",
    "staging",
    "stat",
    "static",
    "statistics",
    "stats",
    "status",
    "store",
    "style",
    "styles",
    "stylesheet",
    "stylesheets",
    "subdomain",
    "subscribe",
    "sudo",
    "super",
    "superuser",
    "support",
    "survey",
    "sync",
    "sysadmin",
    "sysadmin",
    "system",
    "tablet",
    "tag",
    "tags",
    "team",
    "telnet",
    "terms",
    "terms_of_use",
    "test",
    "testimonials",
    "theme",
    "themes",
    "today",
    "tools",
    "topic",
    "topics",
    "tour",
    "training",
    "translate",
    "translations",
    "trending",
    "trial",
    "true",
    "umac_128",
    "umac_128_etm",
    "umac_64",
    "umac_64_etm",
    "undefined",
    "unfollow",
    "unlike",
    "unsubscribe",
    "update",
    "upgrade",
    "usenet",
    "user",
    "username",
    "users",
    "uucp",
    "var",
    "verify",
    "video",
    "view",
    "void",
    "vote",
    "vpn",
    "webmail",
    "webmaster",
    "website",
    "widget",
    "widgets",
    "wiki",
    "wpad",
    "write",
    "www",
    "www_data",
    "www1",
    "www2",
    "www3",
    "www4",
    "you",
    "yourname",
    "yourusername",
    "zlib",
    "abbo",
    "abo",
    "beeyotch",
    "biatch",
    "bitch",
    "chinaman",
    "chinamen",
    "chink",
    "coolie",
    "coon",
    "crazie",
    "crazy",
    "crip",
    "cuck",
    "cunt",
    "dago",
    "daygo",
    "dego",
    "dick",
    "douchebag",
    "dumb",
    "dyke",
    "eskimo",
    "fag",
    "faggot",
    "fatass",
    "fatso",
    "gash",
    "gimp",
    "golliwog",
    "gook",
    "goy",
    "goyim",
    "gyp",
    "gypsy",
    "half_breed",
    "halfbreed",
    "heeb",
    "homo",
    "hooker",
    "idiot",
    "insane",
    "insanitie",
    "insanity",
    "jap",
    "kaffer",
    "kaffir",
    "kaffir",
    "kaffre",
    "kafir",
    "kike",
    "kraut",
    "lame",
    "lardass",
    "lesbo",
    "lunatic",
    "mick",
    "negress",
    "negro",
    "nig",
    "nig_nog",
    "nigga",
    "nigger",
    "nigguh",
    "nip",
    "pajeet",
    "paki",
    "pickaninnie",
    "pickaninny",
    "prostitute",
    "pussie",
    "pussy",
    "raghead",
    "retard",
    "sambo",
    "shemale",
    "skank",
    "slut",
    "soyboy",
    "spade",
    "sperg",
    "spic",
    "spook",
    "squaw",
    "street_shitter",
    "tard",
    "tits",
    "titt",
    "trannie",
    "tranny",
    "twat",
    "wetback",
    "whore",
    "wigger",
    "wop",
    "yid",
    "zog",
    "2g1c",
    "2_girls_1_cup",
    "acrotomophilia",
    "alabama_hot_pocket",
    "alaskan_pipeline",
    "anal",
    "anilingus",
    "anus",
    "apeshit",
    "arsehole",
    "ass",
    "asshole",
    "assmunch",
    "auto_erotic",
    "autoerotic",
    "babeland",
    "baby_batter",
    "baby_juice",
    "ball_gag",
    "ball_gravy",
    "ball_kicking",
    "ball_licking",
    "ball_sack",
    "ball_sucking",
    "bangbros",
    "bangbus",
    "bareback",
    "barely_legal",
    "barenaked",
    "bastard",
    "bastardo",
    "bastinado",
    "bbw",
    "bdsm",
    "beaner",
    "beaners",
    "beaver_cleaver",
    "beaver_lips",
    "beastiality",
    "bestiality",
    "big_black",
    "big_breasts",
    "big_knockers",
    "big_tits",
    "bimbos",
    "birdlock",
    "bitch",
    "bitches",
    "black_cock",
    "blonde_action",
    "blonde_on_blonde_action",
    "blowjob",
    "blow_job",
    "blow_your_load",
    "blue_waffle",
    "blumpkin",
    "bollocks",
    "bondage",
    "boner",
    "boob",
    "boobs",
    "booty_call",
    "brown_showers",
    "brunette_action",
    "bukkake",
    "bulldyke",
    "bullet_vibe",
    "bullshit",
    "bung_hole",
    "bunghole",
    "busty",
    "butt",
    "buttcheeks",
    "butthole",
    "camel_toe",
    "camgirl",
    "camslut",
    "camwhore",
    "carpet_muncher",
    "carpetmuncher",
    "chocolate_rosebuds",
    "cialis",
    "circlejerk",
    "cleveland_steamer",
    "clit",
    "clitoris",
    "clover_clamps",
    "clusterfuck",
    "cock",
    "cocks",
    "coprolagnia",
    "coprophilia",
    "cornhole",
    "coon",
    "coons",
    "creampie",
    "cum",
    "cumming",
    "cumshot",
    "cumshots",
    "cunnilingus",
    "cunt",
    "darkie",
    "date_rape",
    "daterape",
    "deep_throat",
    "deepthroat",
    "dendrophilia",
    "dick",
    "dildo",
    "dingleberry",
    "dingleberries",
    "dirty_pillows",
    "dirty_sanchez",
    "doggie_style",
    "doggiestyle",
    "doggy_style",
    "doggystyle",
    "dog_style",
    "dolcett",
    "domination",
    "dominatrix",
    "dommes",
    "donkey_punch",
    "double_dong",
    "double_penetration",
    "dp_action",
    "dry_hump",
    "dvda",
    "eat_my_ass",
    "ecchi",
    "ejaculation",
    "erotic",
    "erotism",
    "escort",
    "eunuch",
    "fag",
    "faggot",
    "fecal",
    "felch",
    "fellatio",
    "feltch",
    "female_squirting",
    "femdom",
    "figging",
    "fingerbang",
    "fingering",
    "fisting",
    "foot_fetish",
    "footjob",
    "frotting",
    "fuck",
    "fuck_buttons",
    "fuckin",
    "fucking",
    "fucktards",
    "fudge_packer",
    "fudgepacker",
    "futanari",
    "gangbang",
    "gang_bang",
    "gay_sex",
    "genitals",
    "giant_cock",
    "girl_on",
    "girl_on_top",
    "girls_gone_wild",
    "goatcx",
    "goatse",
    "god_damn",
    "gokkun",
    "golden_shower",
    "goodpoop",
    "goo_girl",
    "goregasm",
    "grope",
    "group_sex",
    "g_spot",
    "guro",
    "hand_job",
    "handjob",
    "hard_core",
    "hardcore",
    "hentai",
    "homoerotic",
    "honkey",
    "hooker",
    "horny",
    "hot_carl",
    "hot_chick",
    "how_to_kill",
    "how_to_murder",
    "huge_fat",
    "humping",
    "incest",
    "intercourse",
    "jack_off",
    "jail_bait",
    "jailbait",
    "jelly_donut",
    "jerk_off",
    "jigaboo",
    "jiggaboo",
    "jiggerboo",
    "jizz",
    "juggs",
    "kike",
    "kinbaku",
    "kinkster",
    "kinky",
    "knobbing",
    "leather_restraint",
    "leather_straight_jacket",
    "lemon_party",
    "livesex",
    "lolita",
    "lovemaking",
    "make_me_come",
    "male_squirting",
    "masturbate",
    "masturbating",
    "masturbation",
    "menage_a_trois",
    "milf",
    "missionary_position",
    "mong",
    "motherfucker",
    "mound_of_venus",
    "mr_hands",
    "muff_diver",
    "muffdiving",
    "nambla",
    "nawashi",
    "negro",
    "neonazi",
    "nigga",
    "nigger",
    "nig_nog",
    "nimphomania",
    "nipple",
    "nipples",
    "nsfw",
    "nsfw_images",
    "nude",
    "nudity",
    "nutten",
    "nympho",
    "nymphomania",
    "octopussy",
    "omorashi",
    "one_cup_two_girls",
    "one_guy_one_jar",
    "orgasm",
    "orgy",
    "paedophile",
    "paki",
    "panties",
    "panty",
    "pedobear",
    "pedophile",
    "pegging",
    "penis",
    "phone_sex",
    "piece_of_shit",
    "pikey",
    "pissing",
    "piss_pig",
    "pisspig",
    "playboy",
    "pleasure_chest",
    "pole_smoker",
    "ponyplay",
    "poof",
    "poon",
    "poontang",
    "punany",
    "poop_chute",
    "poopchute",
    "porn",
    "porno",
    "pornography",
    "prince_albert_piercing",
    "pthc",
    "pubes",
    "pussy",
    "queaf",
    "queef",
    "quim",
    "raghead",
    "raging_boner",
    "rape",
    "raping",
    "rapist",
    "rectum",
    "reverse_cowgirl",
    "rimjob",
    "rimming",
    "rosy_palm",
    "rosy_palm_and_her_5_sisters",
    "rusty_trombone",
    "sadism",
    "santorum",
    "scat",
    "schlong",
    "scissoring",
    "semen",
    "sex",
    "sexcam",
    "sexo",
    "sexy",
    "sexual",
    "sexually",
    "sexuality",
    "shaved_beaver",
    "shaved_pussy",
    "shemale",
    "shibari",
    "shit",
    "shitblimp",
    "shitty",
    "shota",
    "shrimping",
    "skeet",
    "slanteye",
    "slut",
    "s&m",
    "smut",
    "snatch",
    "snowballing",
    "sodomize",
    "sodomy",
    "spastic",
    "spic",
    "splooge",
    "splooge_moose",
    "spooge",
    "spread_legs",
    "spunk",
    "strap_on",
    "strapon",
    "strappado",
    "strip_club",
    "style_doggy",
    "suck",
    "sucks",
    "suicide_girls",
    "sultry_women",
    "swastika",
    "swinger",
    "tainted_love",
    "taste_my",
    "tea_bagging",
    "threesome",
    "throating",
    "thumbzilla",
    "tied_up",
    "tight_white",
    "tit",
    "tits",
    "titties",
    "titty",
    "tongue_in_a",
    "topless",
    "tosser",
    "towelhead",
    "tranny",
    "tribadism",
    "tub_girl",
    "tubgirl",
    "tushy",
    "twat",
    "twink",
    "twinkie",
    "two_girls_one_cup",
    "undressing",
    "upskirt",
    "urethra_play",
    "urophilia",
    "vagina",
    "venus_mound",
    "viagra",
    "vibrator",
    "violet_wand",
    "vorarephilia",
    "voyeur",
    "voyeurweb",
    "voyuer",
    "vulva",
    "wank",
    "wetback",
    "wet_dream",
    "white_power",
    "whore",
    "worldsex",
    "wrapping_men",
    "wrinkled_starfish",
    "xx",
    "xxx",
    "yaoi",
    "yellow_showers",
    "yiffy",
    "zoophilia",
    "anal",
    "anus",
    "arse",
    "ass",
    "ballsack",
    "balls",
    "bastard",
    "bitch",
    "biatch",
    "bloody",
    "blowjob",
    "blow_job",
    "bollock",
    "bollok",
    "boner",
    "boob",
    "bugger",
    "bum",
    "butt",
    "buttplug",
    "clitoris",
    "cock",
    "coon",
    "crap",
    "cunt",
    "damn",
    "dick",
    "dildo",
    "dyke",
    "fag",
    "feck",
    "fellate",
    "fellatio",
    "felching",
    "fuck",
    "f_u_c_k",
    "fudgepacker",
    "fudge_packer",
    "flange",
    "Goddamn",
    "God_damn",
    "hell",
    "homo",
    "jerk",
    "jizz",
    "knobend",
    "knob_end",
    "labia",
    "lmao",
    "lmfao",
    "muff",
    "nigger",
    "nigga",
    "omg",
    "penis",
    "piss",
    "poop",
    "prick",
    "pube",
    "pussy",
    "queer",
    "scrotum",
    "sex",
    "shit",
    "s_hit",
    "sh1t",
    "slut",
    "smegma",
    "spunk",
    "tit",
    "tosser",
    "turd",
    "twat",
    "vagina",
    "wank",
    "whore",
    "wtf",
  ];

}
