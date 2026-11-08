<?php
session_start();

$verdicts = [
    fn($m) => "“{$m}” goes below the main plugin. Assets before, plugin after. Always.",
    fn($m) => "“{$m}” is a duplicate. You already have it. Check twice, delete once.",
    fn($m) => "Place “{$m}” as late as possible, then pray to the Mojave.",
    fn($m) => "“{$m}” conflicts with something you installed at 2 a.m. Bisect.",
    fn($m) => "The Oracle sees no problems with “{$m}”. This is suspicious.",
    fn($m) => "“{$m}” belongs above everything it overrides. Sort, don’t shuffle.",
];
$quips = [
    "It’s fine. Probably a missing master.",
    "Have you tried turning the load order off and on again?",
    "Bisect. Bisect. Bisect.",
    "The Mojave giveth, the Mojave crasheth.",
    "Ah, the classic.",
    "Your mods are fine. Mostly. Maybe.",
];

$_SESSION['crashes'] = $_SESSION['crashes'] ?? 0;
$verdict = '';
$quip = '';
$modValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'oracle') {
        $mod = trim($_POST['mod'] ?? '');
        $modValue = $mod;
        $name = $mod !== '' ? $mod : 'that mod';
        $verdict = '> ' . $verdicts[array_rand($verdicts)]($name);
    } elseif ($action === 'crash') {
        $_SESSION['crashes']++;
        $quip = $quips[$_SESSION['crashes'] % count($quips)];
    }
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Forsythe’s Terminal</title>
<style>
:root{--bg:#f3f1e6;--fg:#1f5a2a;--dim:#5b7a60;--card:#e6e3d3;--line:#9db89f;--accent:#b8620f}
@media (prefers-color-scheme:dark){:root{--bg:#07110a;--fg:#4cff72;--dim:#2f9e49;--card:#0b1c10;--line:#1d5a2c;--accent:#ffb84c}}
body{margin:0;background:var(--bg);color:var(--fg);font-family:"Courier New",ui-monospace,monospace;line-height:1.55}
main{max-width:680px;margin:0 auto;padding:24px 18px 48px}
h1{font-size:1.6rem;margin:0 0 4px;letter-spacing:.05em}
.sub{color:var(--dim);margin:0 0 24px}
.cursor{display:inline-block;width:.6em;background:var(--fg);animation:b 1s steps(2) infinite}
@keyframes b{50%{opacity:0}}
section{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:16px;margin-bottom:16px}
h2{font-size:1rem;margin:0 0 10px;color:var(--accent);text-transform:uppercase;letter-spacing:.08em}
button{font:inherit;background:transparent;color:var(--fg);border:1px solid var(--fg);padding:8px 14px;border-radius:6px;cursor:pointer}
button:hover{background:var(--fg);color:var(--bg)}
input{font:inherit;background:var(--bg);color:var(--fg);border:1px solid var(--line);padding:8px;border-radius:6px;width:100%;box-sizing:border-box;margin-bottom:10px}
.out{margin-top:12px;min-height:1.6em;color:var(--accent)}
</style>
</head>
<body>
<main>
  <h1>FORSYTHE<span class="cursor">&nbsp;</span></h1>
  <p class="sub">// personal modding tool for me! :></p>

  <section>
    <h2>About</h2>
    <p>Hi, I’m Forsythe. I tinker with PC games and mods, and I take load order very seriously.</p>
  </section>

  <section>
    <h2>Load Order Oracle</h2>
    <p>Type a mod name. The Oracle will tell you where it belongs. Probably.</p>
    <form method="post">
      <input type="hidden" name="action" value="oracle">
      <input name="mod" value="<?= e($modValue) ?>" placeholder="e.g. STARS Asset Pack" autocomplete="off">
      <button type="submit">Consult the Oracle</button>
    </form>
    <div class="out"><?= e($verdict) ?></div>
  </section>

  <section>
    <h2>Crash Counter</h2>
    <p>Times the game crashed before the main menu: <strong><?= (int)$_SESSION['crashes'] ?></strong></p>
    <form method="post">
      <input type="hidden" name="action" value="crash">
      <button type="submit">Another one</button>
    </form>
    <div class="out" style="color:var(--dim)"><?= e($quip) ?></div>
  </section>
</main>
</body>
</html>