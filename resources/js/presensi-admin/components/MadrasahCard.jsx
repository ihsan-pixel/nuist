import React from 'react';

const MadrasahCard = ({ madrasahData, selectedDate }) => {
    const { madrasah, presensi } = madrasahData;

    const handleMadrasahClick = () => {
        // Trigger modal opening - will be handled by parent
        const event = new CustomEvent('openMadrasahModal', {
            detail: { madrasahId: madrasah.id, madrasahName: madrasah.name }
        });
        window.dispatchEvent(event);
    };

    const handleUserClick = (userId, userName) => {
        // Trigger user modal opening
        const event = new CustomEvent('openUserModal', {
            detail: { userId, userName }
        });
        window.dispatchEvent(event);
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

    return (
        <div className="col-12 col-sm-6 col-md-4 col-lg-2 col-xl-2 mb-4">
            <div className="card h-100">
                <div className="card-header d-flex justify-content-between align-items-center">
                    <h6 className="card-title mb-0 small">
                        <i className="bx bx-building me-1"></i>
                        <span
                            style={{ cursor: 'pointer', textDecoration: 'underline' }}
                            onClick={handleMadrasahClick}
                        >
                            {madrasah.name}
                        </span>
                    </h6>
                    <small className="text-muted">{presensi.length} guru</small>
                </div>
                <div className="card-body">
                    <div className="table-responsive" style={{ maxHeight: '300px', overflowY: 'auto' }}>
                        <table className="table table-sm table-bordered">
                            <thead className="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {presensi.length > 0 ? (
                                    presensi.map((guru, index) => (
                                        <tr key={index}>
                                            <td className="small">
                                                <span
                                                    style={{ cursor: 'pointer', textDecoration: 'underline' }}
                                                    onClick={() => handleUserClick(guru.user_id, guru.nama)}
                                                >
                                                    {guru.nama}
                                                </span>
                                            </td>
                                            <td className="small">
                                                {getStatusBadge(guru.status)}
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="2" className="text-center text-muted small">
                                            <small>Tidak ada tenaga pendidik</small>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default MadrasahCard;
