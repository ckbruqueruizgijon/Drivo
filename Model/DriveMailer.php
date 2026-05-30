<?php
class DriveMailer
{
    // Detecta el dominio del servidor y construye el remitente automáticamente
    private static function remitente(): string
    {
        $dominio = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $dominio = str_replace('www.', '', $dominio);
        return 'no-reply@' . $dominio;
    }

    // Cabeceras comunes para todos los correos
    private static function cabeceras(string $de): string
    {
        $cab  = "From: Drivo <{$de}>\r\n";
        $cab .= "Reply-To: {$de}\r\n";
        $cab .= "MIME-Version: 1.0\r\n";
        $cab .= "Content-Type: text/html; charset=UTF-8\r\n";
        $cab .= "X-Mailer: PHP/" . phpversion();
        return $cab;
    }

    // Envía el correo de bienvenida al registrar una cuenta nueva
    public static function enviarBienvenida(string $email, string $nombre): bool
    {
        $de        = self::remitente();
        $asunto    = '=?UTF-8?B?' . base64_encode('¡Bienvenido/a a Drivo!') . '?=';
        $cuerpo    = self::plantillaBienvenida($nombre);
        $cabeceras = self::cabeceras($de);

        return mail($email, $asunto, $cuerpo, $cabeceras);
    }

    // Envía el código de 6 dígitos para recuperar la contraseña
    public static function enviarCodigo(string $email, string $nombre, string $codigo): bool
    {
        $de        = self::remitente();
        $asunto    = '=?UTF-8?B?' . base64_encode('Código de recuperación de contraseña — Drivo') . '?=';
        $cuerpo    = self::plantillaCodigo($nombre, $codigo);
        $cabeceras = self::cabeceras($de);

        return mail($email, $asunto, $cuerpo, $cabeceras);
    }

    // Envía la confirmación de una reserva al cliente
    public static function enviarConfirmacionReserva(
        string $email,
        string $nombre,
        string $coche,
        string $fechaInicio,
        string $fechaFin,
        float  $precioTotal
    ): bool {
        $de        = self::remitente();
        $asunto    = '=?UTF-8?B?' . base64_encode('¡Reserva confirmada! — Drivo') . '?=';
        $cuerpo    = self::plantillaReserva($nombre, $coche, $fechaInicio, $fechaFin, $precioTotal);
        $cabeceras = self::cabeceras($de);

        return mail($email, $asunto, $cuerpo, $cabeceras);
    }


    // ── Plantillas HTML ──────────────────────────────────────────────────────

    private static function plantillaBienvenida(string $nombre): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head><meta charset="UTF-8"></head>
        <body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
            <tr><td align="center">
              <table width="580" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                <tr>
                  <td style="background:#152D51;padding:32px 40px;text-align:center;">
                    <h1 style="color:#ffffff;margin:0;font-size:28px;letter-spacing:2px;">DRIVO</h1>
                    <p style="color:#7BD5AB;margin:6px 0 0;font-size:13px;">Tu alquiler de coches de confianza</p>
                  </td>
                </tr>
                <tr>
                  <td style="padding:40px;">
                    <h2 style="color:#152D51;margin:0 0 16px;">¡Hola, {$nombre}! 👋</h2>
                    <p style="color:#444;line-height:1.7;margin:0 0 20px;">
                      Gracias por unirte a <strong>Drivo</strong>. Tu cuenta ha sido creada correctamente y ya puedes disfrutar de todos nuestros servicios de alquiler de vehículos.
                    </p>
                    <p style="color:#444;line-height:1.7;margin:0 0 32px;">
                      Explora nuestra flota y reserva el coche que mejor se adapte a ti. ¡Te esperamos en la carretera!
                    </p>
                    <div style="text-align:center;">
                      <a href="http://{$_SERVER['SERVER_NAME']}/Proyecto/Controller/login.php"
                         style="background:#152D51;color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:8px;font-weight:bold;font-size:15px;display:inline-block;">
                        Acceder a mi cuenta
                      </a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style="background:#f4f6f9;padding:20px 40px;text-align:center;">
                    <p style="color:#999;font-size:12px;margin:0;">© 2026 Drivo · Todos los derechos reservados</p>
                  </td>
                </tr>
              </table>
            </td></tr>
          </table>
        </body>
        </html>
        HTML;
    }

    private static function plantillaCodigo(string $nombre, string $codigo): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head><meta charset="UTF-8"></head>
        <body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
            <tr><td align="center">
              <table width="580" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                <tr>
                  <td style="background:#152D51;padding:32px 40px;text-align:center;">
                    <h1 style="color:#ffffff;margin:0;font-size:28px;letter-spacing:2px;">DRIVO</h1>
                    <p style="color:#7BD5AB;margin:6px 0 0;font-size:13px;">Recuperación de contraseña</p>
                  </td>
                </tr>
                <tr>
                  <td style="padding:40px;">
                    <h2 style="color:#152D51;margin:0 0 16px;">Hola, {$nombre}</h2>
                    <p style="color:#444;line-height:1.7;margin:0 0 24px;">
                      Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Usa el siguiente código:
                    </p>
                    <div style="text-align:center;margin:0 0 28px;">
                      <span style="display:inline-block;background:#f0f4ff;border:2px dashed #152D51;border-radius:12px;padding:18px 40px;font-size:38px;font-weight:bold;letter-spacing:10px;color:#152D51;">
                        {$codigo}
                      </span>
                    </div>
                    <p style="color:#888;font-size:13px;text-align:center;margin:0 0 28px;">
                      ⏱ Este código caduca en <strong>15 minutos</strong>.
                    </p>
                    <p style="color:#444;line-height:1.7;margin:0;">
                      Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña no se modificará.
                    </p>
                  </td>
                </tr>
                <tr>
                  <td style="background:#f4f6f9;padding:20px 40px;text-align:center;">
                    <p style="color:#999;font-size:12px;margin:0;">© 2026 Drivo · Todos los derechos reservados</p>
                  </td>
                </tr>
              </table>
            </td></tr>
          </table>
        </body>
        </html>
        HTML;
    }

    private static function plantillaReserva(string $nombre, string $coche, string $fechaInicio, string $fechaFin, float $precioTotal): string
    {
        $precio = number_format($precioTotal, 2, ',', '.') . ' €';
        $fInicio = date('d/m/Y', strtotime($fechaInicio));
        $fFin    = date('d/m/Y', strtotime($fechaFin));
        $dias    = (int) ((strtotime($fechaFin) - strtotime($fechaInicio)) / 86400) + 1;
        $textDias = $dias === 1 ? '1 día' : "{$dias} días";

        return <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head><meta charset="UTF-8"></head>
        <body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
            <tr><td align="center">
              <table width="580" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                <tr>
                  <td style="background:#152D51;padding:32px 40px;text-align:center;">
                    <h1 style="color:#ffffff;margin:0;font-size:28px;letter-spacing:2px;">DRIVO</h1>
                    <p style="color:#7BD5AB;margin:6px 0 0;font-size:13px;">Confirmación de reserva</p>
                  </td>
                </tr>
                <tr>
                  <td style="padding:40px;">
                    <h2 style="color:#152D51;margin:0 0 8px;">¡Reserva confirmada! 🚗</h2>
                    <p style="color:#444;line-height:1.7;margin:0 0 28px;">
                      Hola <strong>{$nombre}</strong>, tu reserva ha sido procesada correctamente. Aquí tienes el resumen:
                    </p>
                    <!-- Tabla de detalles -->
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8edf3;border-radius:10px;overflow:hidden;margin-bottom:28px;">
                      <tr style="background:#f0f4ff;">
                        <td style="padding:14px 20px;font-weight:bold;color:#152D51;width:40%;">Vehículo</td>
                        <td style="padding:14px 20px;color:#333;">{$coche}</td>
                      </tr>
                      <tr>
                        <td style="padding:14px 20px;font-weight:bold;color:#152D51;border-top:1px solid #e8edf3;">Fecha de recogida</td>
                        <td style="padding:14px 20px;color:#333;border-top:1px solid #e8edf3;">{$fInicio}</td>
                      </tr>
                      <tr style="background:#f0f4ff;">
                        <td style="padding:14px 20px;font-weight:bold;color:#152D51;border-top:1px solid #e8edf3;">Fecha de devolución</td>
                        <td style="padding:14px 20px;color:#333;border-top:1px solid #e8edf3;">{$fFin}</td>
                      </tr>
                      <tr>
                        <td style="padding:14px 20px;font-weight:bold;color:#152D51;border-top:1px solid #e8edf3;">Duración</td>
                        <td style="padding:14px 20px;color:#333;border-top:1px solid #e8edf3;">{$textDias}</td>
                      </tr>
                      <tr style="background:#152D51;">
                        <td style="padding:16px 20px;font-weight:bold;color:#ffffff;border-top:1px solid #e8edf3;font-size:15px;">Total pagado</td>
                        <td style="padding:16px 20px;color:#7BD5AB;font-weight:bold;font-size:18px;">{$precio}</td>
                      </tr>
                    </table>
                    <p style="color:#666;font-size:13px;line-height:1.6;margin:0;">
                      Si tienes cualquier duda puedes responder a este correo o contactarnos a través de nuestra web. ¡Buen viaje!
                    </p>
                  </td>
                </tr>
                <tr>
                  <td style="background:#f4f6f9;padding:20px 40px;text-align:center;">
                    <p style="color:#999;font-size:12px;margin:0;">© 2026 Drivo · Todos los derechos reservados</p>
                  </td>
                </tr>
              </table>
            </td></tr>
          </table>
        </body>
        </html>
        HTML;
    }
}
