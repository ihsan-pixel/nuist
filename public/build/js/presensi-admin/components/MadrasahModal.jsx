import React, { useState, useEffect, useRef } from 'react';
import { MapContainer, TileLayer, Marker, Popup, GeoJSON } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';

const MadrasahModal = ({ isOpen, onClose, madrasahId, madrasahName, selectedDate }) => {
    const [madrasahInfo, setMadrasahInfo] = useState(null);
    const [tenagaPendidik, setTenagaPendidik] = useState([]);
    const mapRef = useRef(null);

    useEffect(() => {
        if (isOpen && madrasahId) {
            fetchMadrasahDetail();
        }
    }, [isOpen, madrasahId, selectedDate]);

    const fetchMadrasahDetail = async () => {
        try {
            const response = await fetch(`/presensi-admin/madrasah-detail/${madrasahId}?date=${selectedDate}`);
            const data = await response.json();
            setMadrasahInfo(data.madrasah);
            setTenagaPendidik(data.tenaga_pendidik);
        } catch (error) {
            console.error('Error fetching madrasah detail:', error);
        }
    };

    const getStatusBadge = (status) => {
        switch (status) {
            case 'hadir':
                return <span className="badge bg-success">Hadir</span>;
            case 'terlambat':
                return <span className="badge bg-warning">Terlambat</span>;
            case 'izin':
                return <span className="badge bg-info">Izin</span>;
            default:
                return <span className="badge bg-secondary">Tidak Hadir</span>;
        }
    };

    const renderMap = () => {
        if (!madrasahInfo) return null;

        const defaultLat = -7.7956;
        const defaultLon = 110.3695;
        const lat = madrasahInfo.latitude || defaultLat;
        const lon = madrasahInfo.longitude || defaultLon;

        return (
            <MapContainer
                center={[lat, lon]}
                zoom={16}
                style={{ height: '250px', width: '100%' }}
                ref={mapRef}
            >
                <TileLayer
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                />
                {madrasahInfo.latitude && madrasahInfo.longitude && (
                    <Marker position={[lat, lon]}>
                        <Popup>{madrasahInfo.name}<br />{madrasahInfo.alamat || ''}</Popup>
                    </Marker>
                )}
                {madrasahInfo.polygon_koordinat && (
                    <GeoJSON data={JSON.parse(madrasahInfo.polygon_koordinat)} />
                )}
            </MapContainer>
        );
    };

    if (!isOpen) return null;

    return (
        <div className="modal fade show" style={{ display: 'block' }} tabIndex="-1">
            <div className="modal-dialog modal-xl">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">Detail Madrasah: {madrasahName}</h5>
                        <button type="button" className="btn-close" onClick={onClose}></button>
                    </div>
                    <div className="modal-body">
                        {madrasahInfo && (
                            <>
                                <div className="row mb-3">
                                    <div className="col-md-6">
                                        <div className="mb-2"><strong>Nama Madrasah:</strong> {madrasahInfo.name}</div>
                                        <div className="mb-2"><strong>SCOD:</strong> {madrasahInfo.scod || '-'}</div>
                                        <div className="mb-2"><strong>Kabupaten:</strong> {madrasahInfo.kabupaten || '-'}</div>
                                        <div className="mb-2"><strong>Alamat:</strong> {madrasahInfo.alamat || '-'}</div>
                                    </div>
                                    <div className="col-md-6">
                                        <div className="mb-2"><strong>Hari KBM:</strong> {madrasahInfo.hari_kbm || '-'}</div>
                                        <div className="mb-2"><strong>Latitude:</strong> {madrasahInfo.latitude || '-'}</div>
                                        <div className="mb-2"><strong>Longitude:</strong> {madrasahInfo.longitude || '-'}</div>
                                        <div className="mb-2">
                                            <strong>Map Link:</strong>
                                            {madrasahInfo.map_link ? (
                                                <a href={madrasahInfo.map_link} target="_blank" rel="noopener noreferrer">Lihat Peta</a>
                                            ) : '-'}
                                        </div>
                                        <div className="mb-2">
                                            <strong>Polygon Koordinat:</strong> {madrasahInfo.polygon_koordinat ? 'Ada (Tersimpan)' : 'Tidak Ada'}
                                        </div>
                                    </div>
                                </div>
                                <div className="mb-3">
                                    <label>Area Poligon Presensi</label>
                                    <div style={{ height: '250px', width: '100%', marginTop: '15px', border: '1px solid #ddd', borderRadius: '4px' }}>
                                        {renderMap()}
                                    </div>
                                    <small className="text-muted">Area poligon presensi madrasah ini.</small>
                                </div>
                                <h6>Daftar Tenaga Pendidik:</h6>
                                <div className="table-responsive" style={{ maxHeight: '400px', overflowY: 'auto' }}>
                                    <table className="table table-sm table-bordered">
                                        <thead className="table-light">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Status Kepegawaian</th>
                                                <th>Status Presensi</th>
                                                <th>Waktu Masuk</th>
                                                <th>Waktu Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {tenagaPendidik.map((guru, index) => (
                                                <tr key={index}>
                                                    <td>{guru.nama}</td>
                                                    <td>{guru.status_kepegawaian || '-'}</td>
                                                    <td>{getStatusBadge(guru.status)}</td>
                                                    <td>{guru.waktu_masuk || '-'}</td>
                                                    <td>{guru.waktu_keluar || '-'}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </>
                        )}
                    </div>
                    <div className="modal-footer">
                        <button type="button" className="btn btn-secondary" onClick={onClose}>Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default MadrasahModal;
