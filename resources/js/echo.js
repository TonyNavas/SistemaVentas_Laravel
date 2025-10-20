import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
    withCredentials: true, // ✅ importantísimo
    authEndpoint: "/broadcasting/auth", // por defecto, pero explícalo
    auth: { headers: { "X-CSRF-TOKEN": csrf } },
});
