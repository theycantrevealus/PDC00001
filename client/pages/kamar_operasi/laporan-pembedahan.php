<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>Operator</label>
                        <input type="text" id="operator" name="operator" class="form-control" placeholder="Operator">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Asisten</label>
                        <input type="text" id="asisten" name="asisten" class="form-control" placeholder="Asisten">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Instrumen</label>
                        <input type="text" id="instrumen" name="instrumen" class="form-control" placeholder="Instrumen">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Laporan Pembedahan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="">Macam Pembedahan</label>
                        <select id="macam_pembedahan" name="macam_pembedahan" class="form-control">
                            <option value="" selected>Pilih Macam Pembedahan</option>
                            <option value="Kecil">Kecil</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Besar">Besar</option>
                            <option value="Khusus">Khusus</option>
                            <option value="Canggih">Canggih</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="">Urgensi</label>
                        <select id="urgensi" name="urgensi" class="form-control">
                            <option value="" selected>Pilih Urgensi</option>
                            <option value="Cyto">Cyto</option>
                            <option value="Sedang">Sedang</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="">Luka Operasi</label>
                        <select id="luka_operasi" name="luka_operasi" class="form-control">
                            <option value="" selected>Pilih Luka Operasi</option>
                            <option value="Bersih">Bersih</option>
                            <option value="Bersih Tercemar">Bersih Tercemar</option>
                            <option value="Tercemar">Tercemar</option>
                            <option value="Kotor">Kotor</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Diagnosa Pra Bedah</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <textarea id="diagnosa_pra_bedah" name="diagnosa_pra_bedah" class="form-control" rows="5"
                            placeholder="-"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Tindakan Pembedahan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <textarea id="tindakan_bedah" name="tindakan_bedah" class="form-control" rows="5"
                            placeholder="-"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Diagnosa Pasca Bedah</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <textarea id="diagnosa_pasca_bedah" name="diagnosa_pasca_bedah" class="form-control" rows="5"
                            placeholder="-"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>Ahli Bius</label>
                        <input id="ahli_bius" type="text" name="ahli_bius" class="form-control" placeholder="Ahli Bius">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Cara Pembiusan</label>
                        <input id="cara_bius" type="text" name="cara_bius" class="form-control"
                            placeholder="Cara Pembiusan">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Posisi Pasien</label>
                        <input id="posisi_pasien" type="text" name="posisi_pasien" class="form-control"
                            placeholder="Posisi Pasien">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>No. Implant</label>
                        <input id="no_implant" type="text" name="no_implant" class="form-control"
                            placeholder="No. Implant">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Mulai</label>
                        <input id="mulai" type="text" name="mulai" class="form-control" placeholder="Mulai">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Selesai</label>
                        <input id="selesai" type="text" name="selesai" class="form-control" placeholder="Selesai">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Lama Pembedahan</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="lama_jam" name="lama_jam"
                                        class="form-control form-control-appended inputan" placeholder="jam">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>jam</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="lama_menit" name="lama_menit"
                                        class="form-control form-control-appended inputan" placeholder="menit">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>OK</label>
                        <input id="ok" type="text" name="ok" class="form-control" placeholder="OK">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Laporan Pembedahan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <textarea id="laporan_pembedahan" name="laporan_pembedahan" class="form-control" rows="5"
                            placeholder="-"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Komplikasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <textarea id="komplikasi" name="komplikasi" class="form-control" rows="5"
                            placeholder="-"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="">Perdarahan</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="perdarahan" name="perdarahan"
                                class="form-control form-control-appended inputan" placeholder="Perdarahan">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>cc/mm</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>Jaringan dikirim ke Patologi</label>
                        <select id="jaringan_patologi" name="jaringan_patologi" class="form-control">
                            <option value="" selected>Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Asal Jaringan</label>
                        <input id="asal_jaringan" type="text" name="asal_jaringan" class="form-control"
                            placeholder="Asal Jaringan">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>