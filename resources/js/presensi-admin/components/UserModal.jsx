import React, { useState, useEffect } from 'react';

const UserModal = ({ isOpen, onClose, userId, userName }) => {
    const [userInfo, setUserInfo] = useState(null);
    const [presensiHistory, setPresensiHistory] = useState([]);
    const [activeTab, setActiveTab] = useState('info');

    useEffect(() => {
        if (isOpen && userId) {
            fetchUserDetail();
        }
    }, [isOpen, userId]);

    const fetchUserDetail = async () => {
        try {
            const response = await fetch(`/presensi-admin/detail/${userId}`);
            const data = await response.json();
            setUserInfo(data.user);
            setPresensiHistory(data.presensi_history);
        } catch (error) {
            console.error('Error fetching user detail:', error);
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
                return <span className="badge bg-secondary">{status || 'Tidak Hadir'}</span>;
        }
    };

    if (!isOpen) return null;

    return (
        <div className="modal fade show" style={{ display: 'block' }} tabIndex="-1">
            <div className="modal-dialog modal-xl">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">Detail Presensi: {userName}</h5>
                        <button type="button" className="btn-close" onClick={onClose}></button>
                    </div>
                    <div className="modal-body">
                        <ul className="nav nav-tabs">
                            <li className="nav-item">
                                <button
                                    className={`nav-link ${activeTab === 'info' ? 'active' : ''}`}
                                    onClick={() => setActiveTab('info')}
                                >
                                    Informasi Pengguna
                                </button>
                            </li>
                            <li className="nav-item">
                                <button
                                    className={`nav-link ${activeTab === 'history' ? 'active' : ''}`}
                                    onClick={() => setActiveTab('history')}
                                >
                                    Riwayat Presensi
                                </button>
                            </li>
                        </ul>
                        <div className="tab-content mt-3">
                            {activeTab === 'info' && userInfo && (
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="mb-2"><strong>Nama:</strong> {userInfo.name}</div>
                                        <div className="mb-2"><strong>Email:</strong> <span className="text-muted">{userInfo.email || '-'}</span></div>
                                        <div className="mb-2"><strong>Madrasah:</strong> {userInfo.madrasah}</div>
                                        <div className="mb-2"><strong>Status Kepegawaian:</strong> {userInfo.status_kepegawaian}</div>
                                    </div>
                                    <div className="col-md-6">
                                        <div className="mb-2"><strong>NIP:</strong> <span className="text-muted">{userInfo.nip || '-'}</span></div>
                                        <div className="mb-2"><strong>NUPTK:</strong> <span className="text-muted">{userInfo.nuptk || '-'}</span></div>
                                        <div className="mb-2"><strong>No HP:</strong> {userInfo.no_hp}</div>
                                    </div>
                                </div>
                            )}
                            {activeTab === 'history' && (
                                <div className="table-responsive" style={{ maxHeight: '400px', overflowY: 'auto' }}>
                                    <table className="table table-sm table-bordered">
                                        <thead className="table-light">
                                            <tr>
                                                <th style={{ width: '100px' }}>Tanggal</th>
                                                <th style={{ width: '80px' }}>Masuk</th>
                                                <th style={{ width: '80px' }}>Keluar</th>
                                                <th style={{ width: '80px' }}>Status</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {presensiHistory.map((presensi, index) => (
                                                <tr key={index}>
                                                    <td>{presensi.tanggal}</td>
                                                    <td>{presensi.waktu_masuk || '-'}</td>
                                                    <td>{presensi.waktu_keluar || '-'}</td>
                                                    <td>{getStatusBadge(presensi.status)}</td>
                                                    <td>{presensi.keterangan || '-'}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            )}
                        </div>
                    </div>
                    <div className="modal-footer">
                        <button type="button" className="btn btn-secondary" onClick={onClose}>Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default UserModal;
