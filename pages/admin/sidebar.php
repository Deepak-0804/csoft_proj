<?php
$data = SidebarService::getSidebarItems();
$departments     = $data['departments'];
$screensByDept   = $data['screensByDept'];

// Current active screen (from URL)
$currentScreen = $_GET['page'] ?? '';
?>

<div class="accordion" id="sidebarAccordion">

<?php foreach ($departments as $dept): 
    $deptId     = $dept['DeptId'];
    $collapseId = "dept" . $deptId;

    // Determine if any screen in this department is active
    $isDeptActive = false;
    if (!empty($screensByDept[$deptId])) {
        foreach ($screensByDept[$deptId] as $scr) {
            if ($scr['ControllerName'] === $currentScreen) {
                $isDeptActive = true;
                break;
            }
        }
    }
?>

  <div class="accordion-item">

    <!-- Department Header -->
    <h2 class="accordion-header" id="heading<?= $deptId ?>">
      <button class="accordion-button <?= $isDeptActive ? '' : 'collapsed' ?>"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#<?= $collapseId ?>"
              aria-expanded="<?= $isDeptActive ? 'true' : 'false' ?>"
              aria-controls="<?= $collapseId ?>">

          <!-- Department Icon -->
          <i class="<?= htmlspecialchars($dept['CssClass']) ?> me-2"></i>

          <?= htmlspecialchars($dept['DeptDisplayName']) ?>
      </button>
    </h2>

    <!-- Screens List -->
    <div id="<?= $collapseId ?>"
         class="accordion-collapse collapse <?= $isDeptActive ? 'show' : '' ?>"
         data-bs-parent="#sidebarAccordion">

      <div class="accordion-body p-0">

        <?php if (!empty($screensByDept[$deptId])): ?>
            <?php foreach ($screensByDept[$deptId] as $scr): 
                $isActiveScreen = ($scr['ControllerName'] === $currentScreen);
            ?>
            
              <a href="index.php?page=<?= $scr['ControllerName'] ?>&area=admin"
                 class="list-group-item list-group-item-action sidebar-item px-4 <?= $isActiveScreen ? 'active' : '' ?>">

                  <!-- Screen Icon -->
                  <i class="<?= htmlspecialchars($scr['CssClass']) ?> me-2"></i>

                  <?= htmlspecialchars($scr['ScreenDisplayName']) ?>
              </a>

            <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </div>

  </div>

<?php endforeach; ?>

</div>
