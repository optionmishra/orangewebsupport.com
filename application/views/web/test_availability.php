<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Details</title>
  <script src="<?= base_url('css/tailwind.css') ?>"></script>
</head>

<body>
  <div class="bg-gray-200">
    <div class="container mx-auto p-4 bg-gray-200">
      <!-- Filter Form -->
      <form method="GET" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Class Filter -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Class</label>
            <select name="class" class="shadow border rounded w-full py-2 px-3">
              <option value="all">All Classes</option>
              <?php foreach ($classes as $class): ?>
                <option value="<?= $class['class'] ?>"
                  <?= ($filters['class'] == $class['class']) ? 'selected' : '' ?>>
                  <?= $class['class'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Test Type Filter -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Test Type</label>
            <select name="test_type" class="shadow border rounded w-full py-2 px-3">
              <?php foreach ($test_types as $key => $type): ?>
                <option value="<?= $key ?>"
                  <?= ($filters['test_type'] == $key) ? 'selected' : '' ?>>
                  <?= $type ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Date Range Filter -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Date Range</label>
            <div class="flex gap-2">
              <input type="date" name="date_start" value="<?= $filters['date_start'] ?>"
                class="shadow border rounded w-full py-2 px-3">
              <input type="date" name="date_end" value="<?= $filters['date_end'] ?>"
                class="shadow border rounded w-full py-2 px-3">
            </div>
          </div>
        </div>
        <div class="mt-4">
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Apply Filters
          </button>
        </div>
      </form>

      <div class="p-2 overflow-scroll rounded-md shadow-md bg-white">
        <table class="w-full min-w-max table-auto overflow-auto">
          <thead class="text-white bg-gray-800 font-semibold">
            <tr>
              <th class="cursor-pointer border-y border-blue-gray-100 bg-blue-gray-50/50 p-4 transition-colors hover:bg-blue-gray-50">
                <p class="antialiased font-sans text-sm text-blue-gray-900 flex items-center justify-between gap-2 font-normal leading-none opacity-70">#
                </p>
              </th>
              <th class="cursor-pointer border-y border-blue-gray-100 bg-blue-gray-50/50 p-4 transition-colors hover:bg-blue-gray-50">
                <p class="antialiased font-sans text-sm text-blue-gray-900 flex items-center justify-between gap-2 font-normal leading-none opacity-70">Student Name
                </p>
              </th>
              <th class="cursor-pointer border-y border-blue-gray-100 bg-blue-gray-50/50 p-4 transition-colors hover:bg-blue-gray-50">
                <p class="antialiased font-sans text-sm text-blue-gray-900 flex items-center justify-between gap-2 font-normal leading-none opacity-70">Email
                </p>
              </th>
              <th class="cursor-pointer border-y border-blue-gray-100 bg-blue-gray-50/50 p-4 transition-colors hover:bg-blue-gray-50">
                <p class="antialiased font-sans text-sm text-blue-gray-900 flex items-center justify-between gap-2 font-normal leading-none opacity-70">Class - Section
                </p>
              </th>
              <th class="cursor-pointer border-y border-blue-gray-100 bg-blue-gray-50/50 p-4 transition-colors hover:bg-blue-gray-50">
                <p class="antialiased font-sans text-sm text-blue-gray-900 flex items-center justify-between gap-2 font-normal leading-none opacity-70">Tests
                </p>
              </th>
            </tr>
          </thead>
          <tbody class="">
            <?php foreach ($availability_data as $key => $row): ?>
              <tr class="border-b border-gray-300 py-2 even:bg-gray-100">
                <td class="border-x border-gray-200 py-2 px-3"><?= $key + 1 ?></td>
                <td class="border-x border-gray-200 py-2 px-3"><?= $row['student_name'] ?></td>
                <td class="border-x border-gray-200 py-2 px-3"><?= $row['email'] ?></td>
                <td class="border-x border-gray-200 py-2 px-3"><?= $row['class_name'] . ' - ' . $row['section_name'] ?></td>
                <td class="border-x border-gray-200 py-2 px-3">
                  <?php if ($row['tests']) { ?>
                    <table>
                      <tbody>
                        <?php foreach ($row['tests'] as $test): ?>
                          <tr class="text-xs">
                            <td class="border border-gray-200 py-2 px-3">
                              <p><?= $test_types[$test['paper_mode']] ?></p>
                            </td>
                            <td class="border border-gray-200 py-2 px-3">
                              <p class="py-1 px-2 rounded-md <?= $test['status']['can_attempt'] ? 'bg-green-500/20 text-green-600' : 'bg-red-500/20 text-red-600' ?>"><?= $test['status']["reason"] ?></p>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  <?php } else { ?>
                    <p class="py-1 px-2 rounded-md bg-red-500/20 text-red-600 text-sm">No tests assigned</p>
                  <?php } ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Handle sorting
        document.querySelectorAll('.sort-header').forEach(header => {
          header.addEventListener('click', function() {
            const sortField = this.dataset.sort;
            const currentSort = new URLSearchParams(window.location.search).get('sort_by');
            const currentDir = new URLSearchParams(window.location.search).get('sort_dir');

            let newDir = 'asc';
            if (sortField === currentSort) {
              newDir = currentDir === 'asc' ? 'desc' : 'asc';
            }

            const url = new URL(window.location.href);
            url.searchParams.set('sort_by', sortField);
            url.searchParams.set('sort_dir', newDir);
            window.location.href = url.toString();
          });
        });
      });
    </script>
</body>

</html>