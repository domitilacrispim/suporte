<?

# Inlcuir comentários e informações sobre o sistema
#
################################################################################
#                                  CHANGELOG                                   #
################################################################################
#  incluir um changelog
################################################################################
// por valerio em 08.01.02
session_register("s_logado");
if ($s_logado==0)
{
        echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;URL='../index.php'\">";
}
else
{
        if (conecta(SQL_SERVER,SQL_DB,SQL_USER,SQL_PASSWD,SYS) != "ok")
        {
                print $retorno;
                exit;
        }
}
?>
