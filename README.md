<h1>Disco Elysium Explorer</h1>

<p>The project is a single-page apllication which allow you to build, read and listen conversation from the game - <a href="https://discoelysium.com/"> Disco Elysium </a> developed by studio <a href="https://zaumstudio.com/">ZA/UM</a>.</p>

<h1>Installation</h1>

<p>To install project on local you will need:</p>
<ul>
    <li>
        Apache or nginx to run backend (made on framework Yii2);
    </li>
    <li>
        Composer to install all dependencies;
    </li>
    <li>
        SQL database. To connect the database to project create file <code>/config/db.php</code> and write here connect parameters. There is an example of file: <code>/config/dbExample.php</code>.;
    </li>
    <li>
        Audio assets;
    </li>
</ul>

<p>All necesary SQL files are in folder <code>/migrations</code>. Each SQL file creates table and fills it with data.</p>

<p>Audio assets are not in this repository because there is too many of them (~50k files). But you can download them from <a href="https://drive.google.com/file/d/1hxRZf4zyn23hcer4_po4X0WyQIlewi5b/view?usp=sharing">Google Drive</a> or just <a href="https://www.reddit.com/user/Leerion">message me</a>.<p>
    
<p> After you got assets extract them in to foldee <code>/web/assets/AudioClip_aac/</code>.</p>

<h1>Demo</h1>
You can also test project <a href="http://194.67.113.22/">online</a>.
