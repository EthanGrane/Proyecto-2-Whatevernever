export async function getMarkerLists() {
    try {
        const res = await axios.get('/api/markersLists/');
        return res.data;
    } catch (err) {
        console.error('[PopupCreateMarker]: ', err.response.data);
        return [];
    }
}

export async function getMarkerListById(id)
{
    if(id == null)
        return {data: "Unlisted"};
    try {
        const res = await axios.get('/api/markersLists/' + id);
        console.log(res.data)
        return res.data;
    } catch (err) {
        console.error('[Composables/useMarkerList]: ', err.response.data);
        return [];
    }
}

export async function createMarkerList(createMarkerList_name, createMarkerList_icon) {
    try {
        if (!createMarkerList_name || createMarkerList_name == "") {
            console.error("[Composables/useMarkerList] Marker list name is requiered!");
            return null;
        }
        if (typeof (createMarkerList_icon) !== 'number')
            createMarkerList_icon = getIdByEmoji(createMarkerList_icon);

        let response = await axios.post("http://127.0.0.1:8000/api/markersLists", {
            "name": createMarkerList_name,
            "emoji_identifier": createMarkerList_icon
        });

        console.log("Success:", response.data);
        return response.data.MarkerList;

    } catch (error) {
        console.log("Error:", error.response || error.message);
        return null;
    }
}


/*
 * EMOJI
 */

export function getEmojiById(id) {
    if (id == null)
        return "📍"
    else
        return emojiDictionary[id];
}

export function getIdByEmoji(emoji) {
    for (const [id, value] of Object.entries(emojiDictionary)) {
        if (value === emoji) {
            return parseInt(id);
        }
    }
    return null;
}

export function generateRandomEmoji() {
    const emojiKeys = Object.keys(emojiDictionary);
    const randomKey = emojiKeys[Math.floor(Math.random() * emojiKeys.length)];
    return emojiDictionary[randomKey];
}

const emojiDictionary = {
    1: "🐶",
    2: "🐱",
    3: "🦊",
    4: "🦁",
    5: "🐮",
    6: "🐸",
    7: "🐵",
    8: "🐧",
    9: "🐯",
    10: "🐻",
    11: "🐨",
    12: "🦄",
    13: "🦓",
    14: "🦘",
    15: "🍎",
    16: "🍔",
    17: "🍕",
    18: "🍣",
    19: "🍪",
    20: "🍩",
    21: "🍇",
    22: "🌮",
    23: "🍉",
    24: "🍒",
    25: "🍓",
    26: "🍋",
    27: "🍍",
    28: "🍊",
    29: "📱",
    30: "💻",
    31: "📷",
    32: "🎧",
    33: "🕹️",
    34: "📦",
    35: "🚗",
    36: "🎁",
    37: "🖨️",
    38: "🗝️",
    39: "💡",
    40: "🎮",
    41: "🎤",
    42: "🧳",
    43: "😀",
    44: "😂",
    45: "😎",
    46: "😍",
    47: "😡",
    48: "😭",
    49: "😴",
    50: "🤯",
    51: "🥰",
    52: "🤪",
    53: "😱",
    54: "😏",
    55: "😜",
    56: "😒",
    57: "☀️",
    58: "🌧️",
    59: "⛈️",
    60: "❄️",
    61: "🌪️",
    62: "🌈",
    63: "🌤️",
    64: "🌙",
    65: "🌫️",
    66: "🌜",
    67: "🌛",
    68: "🌕",
    69: "🌘",
    70: "⚽",
    71: "🏀",
    72: "🏈",
    73: "🎾",
    74: "🏓",
    75: "🥊",
    76: "🏋️",
    77: "🚴",
    78: "🏒",
    79: "🎯",
    80: "🏌️‍♂️",
    81: "🏏",
    82: "🏓",
    83: "🏐",
    84: "❤️",
    85: "💀",
    86: "⭐",
    87: "🔥",
    88: "💧",
    89: "⚡",
    90: "🎵",
    91: "🌀",
    92: "💫",
    93: "🌟",
    94: "✨",
    95: "🦋",
    96: "🔮",
    97: "⚜️"
};
