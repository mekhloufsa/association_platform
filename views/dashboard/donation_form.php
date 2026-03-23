<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
    <a href="<?= $basePath ?>/dashboard" style="color: var(--accent-color); text-decoration: none; display: inline-block; margin-bottom: 2rem;">← Retour au tableau de bord</a>
    
    <div class="glass-panel" style="padding: 2.5rem;">
        <h1 class="gradient-text" style="margin-bottom: 1rem;">Faire un don</h1>
        <p style="color: var(--text-muted); margin-bottom: 2.5rem;">Choisissez une association et soutenez leurs actions au niveau national ou local.</p>

        <!-- Search Bar -->
        <form action="<?= $basePath ?>/dashboard/donation" method="GET" style="margin-bottom: 2rem; display: flex; gap: 1rem;">
            <input type="text" name="search" placeholder="Rechercher une association..." value="<?= htmlspecialchars($search ?? '') ?>" 
                   style="flex: 1; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            <button type="submit" class="btn btn-secondary">Filtrer</button>
            <?php if (!empty($search)): ?>
                <a href="<?= $basePath ?>/dashboard/donation" class="btn btn-secondary" style="background: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.5);">Effacer</a>
            <?php endif; ?>
        </form>

        <form action="<?= $basePath ?>/dashboard/donation" method="POST" id="donationForm">
            <!-- Step 1: Select Association -->
            <div class="form-group" style="margin-bottom: 2.5rem;">
                <label style="display: block; margin-bottom: 1.5rem; font-weight: 600; font-size: 1.1rem;">1. Choisir l'Association</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                    <?php foreach($associations as $assoc): ?>
                        <div class="assoc-card glass-panel" 
                             onclick="selectAssoc(<?= $assoc['id'] ?>, <?= htmlspecialchars(json_encode($assoc['name'])) ?>, <?= htmlspecialchars(json_encode($assoc['sieges'])) ?>)"
                             style="cursor: pointer; padding: 1.2rem; transition: all 0.3s ease; border: 1px solid var(--glass-border);"
                             id="assoc-<?= $assoc['id'] ?>">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--accent-color); display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?= substr($assoc['name'], 0, 1) ?>
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1rem;"><?= htmlspecialchars($assoc['name']) ?></h4>
                                    <p style="margin: 0; font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars(substr($assoc['description'], 0, 50)) ?>...</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="association_id" id="association_id_input" required>
            </div>

            <!-- Step 2: Select Target (National vs Siege) -->
            <div id="targetSection" style="display: none; margin-bottom: 2.5rem; animation: fadeIn 0.4s ease;">
                <label style="display: block; margin-bottom: 1.5rem; font-weight: 600; font-size: 1.1rem;">2. Destination du don</label>
                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                    <button type="button" class="btn btn-secondary target-btn active" onclick="selectTarget('national')" id="btn-target-national" style="flex: 1; border: 1px solid var(--glass-border);">Bureau National</button>
                    <button type="button" class="btn btn-secondary target-btn" onclick="selectTarget('siege')" id="btn-target-siege" style="flex: 1; border: 1px solid var(--glass-border);">Siège Local (Wilaya)</button>
                </div>
                <input type="hidden" name="target_type" id="target_type_input" value="national">

                <div id="siegeSelection" style="display: none; margin-top: 1rem;">
                    <label for="siege_id" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Choisir le siège</label>
                    <select id="siege_id" name="siege_id" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                        <!-- Populated via JS -->
                    </select>
                </div>
            </div>

            <!-- Step 3: Donation Type & Details -->
            <div id="detailsSection" style="display: none; animation: fadeIn 0.4s ease;">
                <label style="display: block; margin-bottom: 1.5rem; font-weight: 600; font-size: 1.1rem;">3. Détails du don</label>
                
                <div id="donationTypeContainer" style="display: none; margin-bottom: 1.5rem;">
                    <div style="display: flex; gap: 1rem;">
                        <button type="button" class="btn btn-secondary type-btn active" onclick="selectDonationType('financial')" id="btn-type-financial" style="flex: 1; border: 1px solid var(--glass-border);">Don Financier</button>
                        <button type="button" class="btn btn-secondary type-btn" onclick="selectDonationType('material')" id="btn-type-material" style="flex: 1; border: 1px solid var(--glass-border);">Don Matériel</button>
                    </div>
                </div>
                <input type="hidden" name="donation_type" id="donation_type_input" value="financial">

                <!-- Financial Fields -->
                <div id="financialFields">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="amount" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Montant du don (DZD)</label>
                            <input type="number" id="amount" name="amount" min="100" step="100" placeholder="Ex: 5000" 
                                   style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Type de carte</label>
                            <select class="glass-panel" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                                <option>CIB / EDAHABIA</option>
                                <option>Visa / Mastercard</option>
                            </select>
                        </div>
                    </div>

                    <!-- Payment Details (Simulation) -->
                    <div id="paymentDetails" class="glass-panel" style="padding: 1.5rem; margin-bottom: 1.5rem; background: rgba(255,255,255,0.03);">
                        <label style="display: block; margin-bottom: 1rem; font-size: 0.9rem; color: var(--accent-color); font-weight: 600;">Informations de paiement</label>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; color: var(--text-muted);">Numéro de carte</label>
                            <input type="text" placeholder="0000 0000 0000 0000" maxlength="19" 
                                   style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white; letter-spacing: 2px;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; color: var(--text-muted);">Date d'expiration</label>
                                <input type="text" placeholder="MM/YY" maxlength="5"
                                       style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white; text-align: center;">
                            </div>
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; color: var(--text-muted);">CVV / Code</label>
                                <input type="password" placeholder="***" maxlength="3"
                                       style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white; text-align: center;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Material Fields -->
                <div id="materialFields" style="display: none;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label for="category" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Catégorie</label>
                            <select name="category" id="category" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                                <option value="Food">Nourriture</option>
                                <option value="Clothing">Vêtements</option>
                                <option value="Medical">Médical</option>
                                <option value="Education">Éducation</option>
                                <option value="Other">Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Quantité / Unité</label>
                            <input type="text" name="quantity" id="quantity" placeholder="Ex: 10 kg, 5 cartons"
                                   style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description détaillée</label>
                        <textarea name="description" id="description" rows="3" placeholder="Décrivez ce que vous souhaitez donner..."
                                  style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white; resize: vertical;"></textarea>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary" style="margin-top: 2rem; width: 100%; padding: 1rem; font-weight: 600;">Confirmer mon don</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.assoc-card:hover { transform: scale(1.02); background: rgba(255,255,255,0.05); }
.assoc-card.selected { border-color: var(--accent-color) !important; background: rgba(99, 102, 241, 0.1) !important; box-shadow: 0 0 15px rgba(99, 102, 241, 0.2); }
.target-btn.active, .type-btn.active { background: var(--accent-color); color: white; border-color: var(--accent-color); }
</style>

<script>
let currentSieges = [];

function selectAssoc(id, name, sieges) {
    console.log('Selecting assoc:', id, name);
    document.querySelectorAll('.assoc-card').forEach(c => c.classList.remove('selected'));
    const card = document.getElementById('assoc-' + id);
    if (card) card.classList.add('selected');
    document.getElementById('association_id_input').value = id;
    
    currentSieges = sieges;
    document.getElementById('targetSection').style.display = 'block';
    document.getElementById('detailsSection').style.display = 'block';
    
    // Reset targets
    selectTarget('national');
}

function selectTarget(type) {
    console.log('Selecting target:', type);
    document.getElementById('target_type_input').value = type;
    document.querySelectorAll('.target-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-target-' + type).classList.add('active');
    
    if (type === 'siege') {
        document.getElementById('siegeSelection').style.display = 'block';
        document.getElementById('donationTypeContainer').style.display = 'block';
        populateSieges();
    } else {
        document.getElementById('siegeSelection').style.display = 'none';
        document.getElementById('donationTypeContainer').style.display = 'none';
        document.getElementById('siege_id').value = '';
        selectDonationType('financial');
    }
}

function populateSieges() {
    const select = document.getElementById('siege_id');
    select.innerHTML = '';
    if (!currentSieges || currentSieges.length === 0) {
        select.innerHTML = '<option value="">Aucun siège disponible</option>';
        return;
    }
    currentSieges.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.wilaya_name + ' - ' + s.address;
        select.appendChild(opt);
    });
}

function selectDonationType(type) {
    console.log('Selecting donation type:', type);
    document.getElementById('donation_type_input').value = type;
    document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-type-' + type).classList.add('active');
    
    if (type === 'material') {
        document.getElementById('materialFields').style.display = 'block';
        document.getElementById('financialFields').style.display = 'none';
        document.getElementById('amount').required = false;
        document.getElementById('description').required = true;
    } else {
        document.getElementById('materialFields').style.display = 'none';
        document.getElementById('financialFields').style.display = 'block';
        document.getElementById('amount').required = true;
        document.getElementById('description').required = false;
    }
}

// Handle pre-selection from URL
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const assocId = urlParams.get('association_id');
    const siegeId = urlParams.get('siege_id');
    
    if (assocId) {
        const card = document.getElementById('assoc-' + assocId);
        if (card) {
            card.click(); // Trigger selectAssoc
            if (siegeId) {
                selectTarget('siege');
                document.getElementById('siege_id').value = siegeId;
            }
        }
    }
};

// Debug form submission
document.getElementById('donationForm').onsubmit = function(e) {
    console.log('Form submitting...', {
        association_id: document.getElementById('association_id_input').value,
        target_type: document.getElementById('target_type_input').value,
        siege_id: document.getElementById('siege_id').value,
        donation_type: document.getElementById('donation_type_input').value,
        amount: document.getElementById('amount').value
    });
};
</script>
