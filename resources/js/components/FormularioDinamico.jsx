import React, { useState, useEffect } from 'react';

export default function FormularioDinamico() {

    const preguntas = [
        'Nombre',
        '¿Cuanto pesas?',
        '¿Cuanto mides?',
        '¿Tienes problemas de corazon?',
        '¿Cuantos dias a la semana puedes entrenar?',
        '¿Cuanto tiempo puedes ir a entrenar?',
        '¿Que edad tienes?',
        '¿Qué objetivo tienes este año?',
    ];

    const [paso, setPaso] = useState(0);
    const [yaEnviado, setYaEnviado] = useState(null);
    const [respuestas, setRespuestas] = useState(
        preguntas.map(p => ({
            pregunta: p,
            respuesta: ''
        }))
    );

    useEffect(() => {
        fetch('/formulario/ya-enviado')
            .then(r => r.json())
            .then(d => setYaEnviado(d.enviado))
            .catch(() => setYaEnviado(false));
    }, []);

    const actualizarRespuesta = (valor) => {
        const nuevas = [...respuestas];
        nuevas[paso].respuesta = valor;
        setRespuestas(nuevas);
    };

    const siguiente = () => {
        if (paso < preguntas.length - 1) {
            setPaso(paso + 1);
        }
    };

    const anterior = () => {
        if (paso > 0) {
            setPaso(paso - 1);
        }
    };

    const finalizar = async () => {
        const response = await fetch('/guardar-respuestas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },
            body: JSON.stringify({ respuestas })
        });

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'resultado.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
        setYaEnviado(true);
    };

    if (yaEnviado === null) {
        return (
            <div className="d-flex justify-content-center align-items-center" style={{ minHeight: 'calc(100vh - 150px)', background: 'transparent' }}>
                <div className="spinner-border text-warning" role="status" />
            </div>
        );
    }

    if (yaEnviado) {
        return (
            <div className="d-flex justify-content-center align-items-center" style={{ minHeight: 'calc(100vh - 150px)', background: 'transparent' }}>
                <div className="p-5 shadow rounded-4 text-center" style={{ width: '100%', maxWidth: '500px', background: 'var(--bg-elevated)', border: '1px solid var(--border-strong)' }}>
                    <div style={{ fontSize: '60px', marginBottom: '16px' }}>✅</div>
                    <h2 className="fw-bold mb-3">Formulario ya enviado</h2>
                    <p className="text-muted mb-4">Ya has completado el cuestionario. Gracias.</p>
                    <a href="/home" className="btn btn-primary">Volver al inicio</a>
                </div>
            </div>
        );
    }

    return (

        <div className="d-flex justify-content-center align-items-center" style={{ minHeight: 'calc(100vh - 150px)', background: 'transparent' }}>

            <div
                className="p-5 shadow rounded-4"
                style={{
                    width: '100%',
                    maxWidth: '700px',
                    background: 'var(--bg-elevated)',
                    border: '1px solid var(--border-strong)'
                }}
            >

                <div className="mb-4">

                    <div className="d-flex justify-content-between mb-2">

                        <span>
                            Pregunta {paso + 1}
                        </span>

                        <span>
                            {preguntas.length}
                        </span>

                    </div>

                    <div className="progress">

                        <div
                            className="progress-bar"
                            style={{
                                width: `${((paso + 1) / preguntas.length) * 100}%`
                            }}
                        />

                    </div>

                </div>

                <div
                    className="text-center"
                    style={{
                        transition: 'all 0.4s ease'
                    }}
                >

                    <h2 className="mb-5 fw-bold">
                        {preguntas[paso]}
                    </h2>

                    <input
                        type="text"
                        className="form-control form-control-lg mb-5"
                        value={respuestas[paso].respuesta}
                        onChange={(e) =>
                            actualizarRespuesta(e.target.value)
                        }
                    />

                    <div className="d-flex justify-content-between">

                        <button
                            className="btn btn-outline-secondary px-4"
                            onClick={anterior}
                            disabled={paso === 0}
                        >
                            Atrás
                        </button>

                        {
                            paso < preguntas.length - 1 ? (

                                <button
                                    className="btn btn-primary px-5"
                                    onClick={siguiente}
                                >
                                    Siguiente
                                </button>

                            ) : (

                                <button
                                    className="btn btn-success px-5"
                                    onClick={finalizar}
                                >
                                    Finalizar
                                </button>
                            )
                        }

                    </div>

                </div>

            </div>

        </div>
    );
}
