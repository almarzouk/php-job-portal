<?php 
require_once 'header.php';

use Models\Job;

$jobModel = new Job();
$query = $_GET['q'] ?? '';
$jobs = $query ? $jobModel->search($query) : $jobModel->getAll();
?>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <h1>Finde deinen Traumjob</h1>
      <p>Entdecke spannende Karrierechancen in ganz Deutschland</p>

      <form method="GET" action="" class="search-bar position-relative" style="z-index: 1000;">
        <input type="text" name="q" id="searchInput" class="form-control"
          placeholder="Suchbegriff, z.B. 'Marketing', 'Berlin'..." value="<?= htmlspecialchars($query) ?>"
          autocomplete="off">
        <button type="submit">Jobs suchen</button>
        <!-- قائمة الاقتراحات -->
        <div id="suggestionsBox" class="list-group position-absolute w-100 mt-2" style="top: 100%; display: none;">
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Job Listings -->
<section class="jobs" id="jobs">
  <div class="container">
    <div class="section-header">
      <h2>🔍 Offene Stellen</h2>
      <p>Entdecke die neuesten Stellenangebote auf unserer Plattform</p>
    </div>

    <div id="jobResults">
      <?php if (count($jobs) > 0): ?>
      <div class="row">
        <?php foreach ($jobs as $job): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="job-card">
            <div class="card-body">
              <div class="job-card-header d-flex justify-content-between align-items-start">
                <div class="d-flex">
                  <div class="company-logo me-3">
                    <i class="fas fa-building"></i>
                  </div>
                  <div>
                    <h5 class="job-title mb-1"><?= htmlspecialchars($job['title']) ?></h5>
                    <div class="company-name"><?= htmlspecialchars($job['company'] ?? 'Unternehmen') ?></div>
                  </div>
                </div>

                <?php if (!empty($_SESSION['user_id'])): ?>
                <?php
                $favoriteModel = new \Models\Favorite();
                $isFavorite = $favoriteModel->isFavorite($_SESSION['user_id'], $job['id']);
              ?>
                <form action="/public/favorite/toggle/<?= $job['id'] ?>" method="POST">
                  <button type="submit" class="btn btn-sm <?= $isFavorite ? 'btn-danger' : 'btn-outline-secondary' ?>"
                    title="Zur Favoritenliste hinzufügen">
                    <i class="fas fa-heart"></i>
                  </button>
                </form>
                <?php endif; ?>
              </div>

              <div class="job-meta mt-3">
                <div class="job-meta-item">
                  <i class="fas fa-map-marker-alt"></i>
                  <span><?= htmlspecialchars($job['location']) ?></span>
                </div>
                <div class="job-meta-item">
                  <i class="fas fa-briefcase"></i>
                  <span><?= htmlspecialchars($job['category']) ?></span>
                </div>
                <div class="job-meta-item">
                  <i class="fas fa-clock"></i>
                  <span><?= htmlspecialchars($job['type'] ?? 'Vollzeit') ?></span>
                </div>
              </div>

              <div class="job-description mt-3">
                <?= mb_strimwidth(strip_tags($job['description']), 0, 100, "...") ?>
              </div>

              <div class="job-tags mt-2">
                <span class="job-tag">Bewerbung einfach</span>
                <span class="job-tag">Sofort verfügbar</span>
              </div>

              <a href="/public/jobs/view/<?= $job['id'] ?>" class="view-job-btn mt-3">Details ansehen</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <p class="text-muted">Zurzeit sind keine Stellen verfügbar.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="cta">
  <div class="container">
    <div class="cta-content">
      <h2>Bereit für den nächsten Karriereschritt?</h2>
      <p>Registriere dich jetzt und bewirb dich mit nur wenigen Klicks auf die besten Jobs in Deutschland!</p>
      <a href="/public/register" class="cta-btn">Jetzt registrieren</a>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="about" id="about">
  <div class="container">
    <div class="about-text">
      <h2>Über unsere Plattform</h2>
      <p>
        Unsere Jobbörse bringt Arbeitssuchende und Arbeitgeber zusammen – schnell, einfach und effizient. Mit einem
        klaren Fokus auf Aus- und Weiterbildungsplätze bieten wir passende Jobangebote für jeden Karriereweg.
      </p>
      <p>
        Wir verstehen die Herausforderungen der heutigen Jobsuche und machen den Prozess so unkompliziert wie möglich.
        Mit unserer benutzerfreundlichen Plattform kannst du dich auf das Wesentliche konzentrieren: deinen
        beruflichen Erfolg.
      </p>
    </div>

    <div class="stats">
      <div class="stat-item">
        <div class="stat-number">5,000+</div>
        <div class="stat-label">Verfügbare Jobs</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">10,000+</div>
        <div class="stat-label">Zufriedene Nutzer</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">1,500+</div>
        <div class="stat-label">Unternehmen</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">85%</div>
        <div class="stat-label">Erfolgsquote</div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'footer.php'; ?>

<script>
const input = document.getElementById('searchInput');
const suggestionsBox = document.getElementById('suggestionsBox');
const jobResults = document.getElementById('jobResults');

input.addEventListener('input', async function() {
  const term = input.value.trim();
  if (term.length < 2) {
    suggestionsBox.style.display = 'none';
    return;
  }

  try {
    const res = await fetch('/public/jobs/suggest?term=' + encodeURIComponent(term));
    const suggestions = await res.json();

    suggestionsBox.innerHTML = '';
    if (suggestions.length === 0) {
      suggestionsBox.style.display = 'none';
      return;
    }

    suggestions.forEach(title => {
      const item = document.createElement('div');
      item.className = 'list-group-item list-group-item-action';
      item.textContent = title;
      item.style.cursor = 'pointer';

      item.addEventListener('click', () => {
        input.value = title;
        suggestionsBox.style.display = 'none';
        performSearch(title);
      });

      suggestionsBox.appendChild(item);
    });

    suggestionsBox.style.display = 'block';
  } catch (err) {
    console.error('Autocomplete error:', err);
  }
});

function performSearch(query) {
  fetch('/public/jobs/ajaxSearch?q=' + encodeURIComponent(query))
    .then(res => res.text())
    .then(html => {
      jobResults.innerHTML = html;
    });
}

document.addEventListener('click', function(e) {
  if (!suggestionsBox.contains(e.target) && e.target !== input) {
    suggestionsBox.style.display = 'none';
  }
});
</script>