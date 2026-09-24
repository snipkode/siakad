<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	/*
	 * Partial: blok atribut EAV dinamis untuk sebuah entitas.
	 * Variabel yang harus di-set dari view pemanggil:
	 *   $eav_fields : array kd_field => definisi field (is_eav=Y, berlaku mode aktif)
	 *   $eav_values : array kd_field => nilai sekarang (default: array())
	 */
	if (empty($eav_fields)) {
		return;
	}
	$cls = "w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-700 bg-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30";
	$eav_values = isset($eav_values) && is_array($eav_values) ? $eav_values : array();
?>
<div class="sm:col-span-2">
	<div class="mb-4 border-t border-slate-100 pt-5">
		<p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Data Tambahan (khusus mode aktif)</p>
	</div>
	<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
		<?php foreach ($eav_fields as $kd => $f):
			$nama = 'eav_'.$kd;
			$val  = isset($eav_values[$kd]) ? $eav_values[$kd] : '';
		?>
			<div>
				<label class="mb-1.5 block text-sm font-medium text-slate-700"><?php echo $f['label']; ?></label>
				<?php if (!empty($f['ref_kategori'])): ?>
					<select name="<?php echo $nama; ?>" class="<?php echo $cls; ?>">
						<option value="">-- Pilih --</option>
						<?php foreach (meta_ref($f['ref_kategori']) as $code => $nm): ?>
							<option value="<?php echo $code; ?>" <?php echo ($val === (string) $code) ? 'selected' : ''; ?>><?php echo $nm; ?></option>
						<?php endforeach; ?>
					</select>
				<?php else: ?>
					<?php
						$tipe = isset($f['tipe_data']) ? $f['tipe_data'] : 'text';
						$html = ($tipe === 'date') ? 'date' : (($tipe === 'number') ? 'number' : 'text');
					?>
					<input type="<?php echo $html; ?>" name="<?php echo $nama; ?>" value="<?php echo html_escape($val); ?>" class="<?php echo $cls; ?>">
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>